var express = require('express');
var HttpStatus = require('http-status-codes');
var mongojs = require('mongojs');
var crypto = require('crypto');
var webshot = require('webshot');
var fs = require('fs');
var app = express();

var db = mongojs('switchblade_dev');

// define collections here
var users = db.collection('users');
var screenshots = db.collection('screenshots');
var screenshot_images = db.collection('screenshot_images');

var delay = 100;

var screenshot_folder = __dirname.replace('resources/nodejs','') + 'public/assets/img/screenshots/';

function auth(req, res, next)
{
    if (req.url === '/favicon.ico') 
    {
	res.end();
    } 
    else if(req.query.apikey)
    {
	console.log('Trying to auth '+ req.query.apikey);
        users.find({api_key: req.query.apikey}, function(err, records)
        { 
            if(!err)
            {
                if(records.length != 0)
                {
                    console.log('Authed');
		    req.user_id = records[0].user_id;
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

function get_screenshot(url, options, res, user_id)
{
    // Start the screenshot object
    screenshots.insert({
	url: url, 
	image_path: null,
	user_id : user_id
    },
    function(err, screenshot)
    { 
	if(!err)
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
                        saveScreenShot(screenshot._id, image_data);
                        
		    });
		}
		else
		{
		    console.log('ERROR : '+ err);
		}
	    });
	}
	else
	{
	     console.log('ERROR : '+ err);
	}
    });
}

app.use(auth);

app.get('/hi', function(req, res) 
{
    var options = {
    shotSize: {
            height: 'all',
            quality : 85
        },
        renderDelay: !req.query.delay ? delay : req.query.delay
    };
    get_screenshot(req.query.url, options, res, req.user_id);
});

app.get('/', function(req, res) 
{
    var options = {
        shotSize: {
            height: 'all',
            quality : 85
        },
        renderDelay: !req.query.delay ? delay : req.query.delay
    };
    get_screenshot(req.query.url, options, res, req.user_id);
});

app.get('/low', function(req, res) 
{
    var options = {
        shotSize: {
            height: 'all',
            quality : 85
        },
        renderDelay: !req.query.delay ? delay : req.query.delay
    };
    get_screenshot(req.query.url, options, res, req.user_id);
});
 
app.listen(7778);

function saveScreenShot(screenshot_id, image_data)
{
    var checksum =  crypto.createHash('md5').update(image_data).digest('hex');
    screenshot_images.findOne({checksum: checksum}, function(err, screenshot)
    {
        if(!err)
        {
            if(screenshot === null)
            {
                // Create a new record for the screenshot with the path
                screenshot_images.insert({
                    checksum: checksum,
                    image_path: screenshot_id + '.jpg'
                },
                function(err, screenshot_image)
                {
                    if(!err)
                    {
                        // Insert is SYNC
                        updateScreenShot(screenshot_id, screenshot_image.image_path);

                        // TODO change extension based on what they request
                        fs.writeFile(screenshot_folder + screenshot_id + '.jpg', image_data, 'binary', function (err) 
                        {
                            if (err) 
                            {
				console.log('ERROR : '+ err);
                            }
                            else
                            {
                                console.log('File Created ' +screenshot_id + '.jpg');
                            }
                        });
                    }
                    else
                    {
                        console.log('ERROR : '+ err);
                    }
                });
            }
            else
            {
                updateScreenShot(screenshot_id, screenshot.image_path);
            }
        }
        else
        {
            console.log('ERROR : '+ err);
        }
    });
}

// Update the screenshot to the correct image path
function updateScreenShot(screenshot_id, image_path)
{
    screenshots.findAndModify({
	query: {
	    _id: screenshot_id
	}, 
	update: {
	    $set: {
		image_path: image_path
	    }
	},
	new: true
    }, 
    function(err) 
    {
	if(err)
	{
	    console.log('ERROR '+ err + ', please contact support!');
	}
    });		
}