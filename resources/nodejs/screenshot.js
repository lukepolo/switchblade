var base_path = __dirname.replace('resources/nodejs', '');
require('dotenv').config({
    path: base_path+'.env'
});

var port = process.env.NODE_SCREENSHOT_PORT,
express = require('express'),
mongoClient = require('mongodb').MongoClient,
temp = require('temp').track(),
webshot = require('webshot'),
fs = require('fs'),
resemble = require('node-resemble-js'),
parse_url = require('url'),

// 1 Hour
cache_time = 3600,
// %
max_diff = 10,

app = express(),
screenshot_folder = base_path + 'public/assets/img/screenshots/';

// Initialize connection once and authenticate
mongoClient.connect('mongodb://@localhost:27017/'+process.env.MONGO_DB, function(err, database) 
{
    if(err)
    {
	throw err;
    }
    else
    {
	// Auth with the admin so we can use other databases
	database.admin()
	.authenticate(process.env.DB_USER, process.env.MONGO_PASS, function(err, result)
	{
	    db = database;
	    app.listen(port);
	    console.log('Screenshots Started ON '+ port);
	});
    }
});

app.use(auth);

app.get('/', function(req, res) 
{
    parsed_url = parse_url.parse(req.query.url);
    
    // we need to add http on to it
    if(parsed_url.protocol === null)
    {
	parsed_url = parse_url.parse('http://'+req.query.url);
    }
    
    req.query.url = parsed_url.hostname + parsed_url.path;
    
    var options = 
    {
        // fonts - apt-get install fontconfig libfontconfig-dev libfontenc-dev libfontenc1 libxfont-dev libxfont1 xfonts-base xfonts-100dpi xfonts-75dpi xfonts-cyrillic ttf-mscorefonts-installer libxext-dev libwayland-dev
        // sudo apt-get install ttf-mscorefonts-installer
	windowSize: {
	    width: req.query.width ? req.query.width: 1500
	},
        shotSize: {
            height: req.query.height ? req.query.height : 'all',
//            quality : 75 -- To use needs to be jpg, issue is that resemble doesn't like JPG's so we will need to change what we are doing there
        },
        renderDelay: req.query.delay ? req.query.delay : 100,
	phantomConfig: {
	    'ignore-ssl-errors': 'true'
	},
	streamType: req.query.type ? req.query.type : 'png',
	cookies: [
	    {
		name: 'ketchurl',
		value: req.secret_key,
		path: '/',
		domain: '.'+parsed_url.hostname
	    }
	],
	userAgent : req.query.mobile ? 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.34 (KHTML, like Gecko) Version/5.1' : 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/534.34 (KHTML, like Gecko) Firefox/31.0 Safari/534.34'
    };
    
    // if we do not pass cache false, then we assume they want a cache
    if(typeof req.query.cache === 'undefined')
    {
	getCachedVersion(req.user_id, req.query.url, options, res);
    }
    else
    {
	getScreenshot(req.user_id, req.query.url, options, req.query.force ? true : false, res);
    }
});
 
function auth(req, res, next)
{
    if (req.url === '/favicon.ico') 
    {
	res.end();
    } 
    else if(req.query.apikey)
    {
	console.log('Trying to auth '+ req.query.apikey);
        db.collection('users')
	.findOne({api_key: req.query.apikey}, function(err, user)
        { 
            if(!err)
            {
                if(user)
                {
                    console.log('Authed');
		    req.user_id = user.user_id;
		    req.secret_key = user.secret_key;
                    next();
                }
                else
                {
                    console.log('Not Authed');
                    res.status(401).send('Not Authorized');
                }
            }
            else
            {
                console.log('ERROR : '+ err);
                res.status(500).send('ERROR '+ err + ', please contact support!');
            }
        });
    }
    else
    {
        res.status(401).send('Not Authorized');
    }
};

function getScreenshot(user_id, url, options, forceUpdate, res)
{
    // Using the webshot API Libary create a stream
    webshot(url, options, function(err, renderStream) 
    {
	if(!err)
	{
	    var image_data = '';
	    renderStream.on('data', function(data) 
	    {
		var chunk = data.toString('binary');
		image_data = image_data + chunk;

		// Return to the browser 
		res.write(chunk, 'binary');
	    });

	    // After the image has been completed we need to store it in our mongo db
	    renderStream.on('end', function() 
	    {
		// Stop the response
		res.end();
		if(image_data)
		{
		    checkScreenShot(user_id, url, options, image_data, forceUpdate);
		}
		else
		{
		    console.log('Failed to grab screenshot');
		}
	    });
	}
	else
	{
	    console.log('ERROR : '+ err);
	    res.end();
	}
    });
}

function checkScreenShot(user_id, url, options, image_data, forceUpdate)
{
    // get the domain
    db.collection('screenshot_revisions')
    .find({
	url: url,
	width : options.windowSize.width,
	height: options.shotSize.height,
	agent : options.userAgent
    }).sort({
	created_at:-1
    })
    .limit(1)
    .toArray(function(err, screenshot_revision)
    {
        if(!err)
        {
	    if(typeof screenshot_revision[0] === 'undefined' || forceUpdate)
            {
                createScreenShotRevision(user_id, url, options, image_data);
            }
            else
            {
		console.log('Now Check to see how simliar they are, otherwise create a revision');
		
                
                temp.open({suffix: '.png'}, function(err, file)
		{
		    console.log(file.path);
		    if (!err) 
		    {
			fs.writeFile(file.path, image_data, 'binary');
			fs.close(file.fd, function(err)
			{
			    if(!err)
			    {
				resemble(file.path).compareTo(screenshot_folder+screenshot_revision[0]._id+'.png')
				.onComplete(function(data)
				{
				    temp.cleanup();
				    console.log(data.misMatchPercentage);
				    if(data.misMatchPercentage > max_diff)
				    {
					createScreenShotRevision(user_id, url, options, image_data);
				    }
				    else
				    {
					// Update Cache Time
					updateCache(screenshot_revision[0]._id);
					createScreenShot(user_id, url, options, screenshot_revision[0]._id);
				    }
				});
			    }
			    else
			    {
				console.log('ERROR : '+ err)
			    }
			});
		    }
		    else
		    {
			console.log('ERROR : '+ err);
		    }
		});
            }
        }
        else
        {
            console.log('ERROR : '+ err);
        }
    });
}

// Update the screenshot to the correct image path
function createScreenShot(user_id, url, options, screenshot_revision_id)
{
    console.log(screenshot_revision_id);
    // Start the screenshot object
    db.collection('screenshots')
    .insert({
	user_id : user_id,
	url: url,
	screenshot_revision_id: screenshot_revision_id.toString(),
	width : options.windowSize.width,
	height: options.shotSize.height,
	agent : options.userAgent,
	created_at: Date.now() / 1000 | 0
    },
    function(err)
    { 
	if(err)
	{
	    console.log('ERROR : '+ err);
	    res.end();
	}
    });
}

function createScreenShotRevision(user_id, url, options, image_data)
{
    console.log('Creating new revision');
    // Create a new record for the screenshot with the path
    db.collection('screenshot_revisions')
    .insert({
	url: url,
	width : options.windowSize.width,
	height: options.shotSize.height,
	agent : options.userAgent,
	created_at: Date.now() / 1000 | 0,
	cache_time: Date.now() / 1000 | 0
    },
    function(err, screenshot_revision)
    {
	// screenshot_revision , assumes its an array since we can do more than 1 insert at a time
	if(!err)
	{
	    createScreenShot(user_id, url, options, screenshot_revision.ops[0]._id);
	    fs.writeFile(screenshot_folder + screenshot_revision.ops[0]._id + '.png', image_data, 'binary', function (err) 
	    {
		if (err) 
		{
		    console.log('ERROR : '+ err);
		}
		else
		{
		    console.log('File Created ' + screenshot_revision.ops[0]._id + '.png');
		}
	    });
	}
	else
	{
	    console.log('ERROR : '+ err);
	}
    });
}

function getCachedVersion(user_id, url, options, res)
{
    // Find a revision with the same url, width, height and cached time is less than the cache_time
    db.collection('screenshot_revisions')
    .find({
	url: url,
	cache_time:{
	    $gt: (Date.now() / 1000 | 0) - cache_time
	},
	width : options.windowSize.width,
	height: options.shotSize.height,
	agent : options.userAgent
    })
    .sort({
	created_at:-1
    })
    .limit(1)
    .toArray(function(err, screenshot_revision)
    {
	if(!err)
	{
	    if(typeof screenshot_revision[0] === 'undefined')
	    {
		getScreenshot(user_id, url, options, false, res);
	    }
	    else
	    {
		console.log('return cache!');
		console.log();
		fs.readFile(screenshot_folder + screenshot_revision[0]._id + '.png', function(err, data) 
		{
		    res.end(data);
		});
	    }
	}
	else
	{
	    console.log('ERROR '+ err + ', please contact support!');
	}
    });
}

function updateCache(screenshot_id)
{
    console.log('Updating Cache Time!');
    db.collection('screenshot_revisions')
    .update({
	_id: screenshot_id
    }, {
	$set: {
	    cache_time: Date.now() / 1000 | 0
	}
    },
    function(err) 
    {
	if(err)
	{
	    console.log('ERROR '+ err + ', please contact support!');
	}
    });		
}
