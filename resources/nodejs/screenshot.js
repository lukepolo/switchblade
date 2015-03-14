var express = require('express');
var HttpStatus = require('http-status-codes');
var mongojs = require('mongojs');
var crypto = require('crypto');
var webshot = require('webshot');
var fs = require('fs');
var resemble = require('node-resemble-js');
var app = express();
var db = mongojs.connect("mongodb://127.0.0.1:27017/switchblade_dev", ["users", "screenshots", "screenshot_images"]);

var delay = 100;
var screenshot_folder = __dirname.replace('fuel/packages/nodejs/app','') + 'public/assets/img/screenshots/';

function auth(req, res, next)
{
    if (req.url === '/favicon.ico') 
    {
	res.end();
    } 
    else if(req.query.apikey)
    {
	console.log('Trying to auth '+ req.query.apikey);
        db.users.find({api_key: req.query.apikey}, function(err, records)
        { 
            if(!err)
            {
                if(records.length != 0)
                {
                    console.log('Authed');
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
        // Most likely just displaying it?
        res.status(401).send('Not Authorized');
    }
};

function get_screenshot(url, options, res, api_key)
{
    // Start the screenshot object
    db.screenshots.insert({
	url: url, 
	image_path: null,
	api_key : api_key
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
		    res.status(500).send('ERROR '+ err + ', please contact support!');
		}
	    });
	}
	else
	{
	     console.log('ERROR : '+ err);
                res.status(500).send('ERROR '+ err + ', please contact support!');
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
    get_screenshot(req.query.url, options, res, req.query.apikey, req.query.user_id);
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
    get_screenshot(req.query.url, options, res, req.query.apikey, req.query.user_id);
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
    get_screenshot(req.query.url, options, res, req.query.apikey, req.query.user_id);
});
 
app.listen(7778);

function saveScreenShot(screenshot_id, image_data)
{
    var checksum =  crypto.createHash('md5').update(image_data).digest('hex');
    db.screenshot_images.findOne({checksum: checksum}, function(err, screenshot)
    {
        if(!err)
        {
            if(screenshot === null)
            {
                // Create a new record for the screenshot with the path
                db.screenshot_images.insert({
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
                                res.status(500).send('ERROR '+ err + ', please contact support!');
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
                        res.status(500).send('ERROR '+ err + ', please contact support!');
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
            res.status(500).send('ERROR '+ err + ', please contact support!');
        }
    });
}

// Update the screenshot to the correct image path
function updateScreenShot(screenshot_id, image_path)
{
    db.screenshots.findAndModify({
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