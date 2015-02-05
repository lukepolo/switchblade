var express = require('express');
var HttpStatus = require('http-status-codes');
var mongojs = require('mongojs');
var crypto = require('crypto');
var webshot = require('webshot');
var fs = require('fs');
var resemble = require('node-resemble-js');
var app = express();
var db = mongojs.connect("mongodb://127.0.0.1:27017/switchblade_dev", ["users", "screenshots", "screenshot_images"]);

var delay = 0;
var screenshot_folder = __dirname.replace('fuel/packages/nodejs/app','') + 'public/assets/img/screenshots/';

function auth(req, res, next)
{
    console.log('Trying to auth '+ req.query.apikey);
    if(req.query.apikey)
    {
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
        console.log('No API Key Sent');
        res.status(401).send('Not Authorized');
    }
};

function get_screenshot(url, options, res, api_key, user_id)
{
    var image_path = null;
    db.screenshots.insert({
	url: url, 
	user_id: user_id,
	screenshot_images_id: null,
	api_key : api_key
    }, 
    function(err, row)
    { 
	if(!err)
	{
	    screenshot_id = row._id;
	    image_path = screenshot_folder + screenshot_id + '.jpg';

	    webshot(url, options, function(err, renderStream) 
	    {
		if(!err)
		{
		    var file = fs.createWriteStream(image_path, {encoding: 'binary'});
		    var image_data;
		    renderStream.on('data', function(data) 
		    {
			image_data = data.toString('binary');

			// Start creating the file
			file.write(image_data, 'binary');

			// Return to the browser 
			res.write(image_data, 'binary');
		    });

		    renderStream.on('end', function() 
		    {
			// Stop writing to file and we can save it
			file.end();

			// Stop the response
			res.end();

			// lets check to make sure the image is unquie...otherwise its a waste of space
			var checksum =  crypto.createHash('md5').update(image_data, 'utf8').digest('hex');

			db.screenshot_images.findOne({checksum: checksum}, function(err, row)
			{
			    if(!err)
			    {
				var screenshot_images_id = null;
				if(row == null)
				{
				    // Create a new record for the screenshot with the path
				    db.screenshot_images.insert({
					checksum: checksum,
					image: image_path
				    },
				    function(err, row)
				    {
					if(!err)
					{
					    // Insert is SYNC (AKA DO NOT MOVE)
					    updateScreenShot(screenshot_id, row._id);
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
				    fs.unlink(image_path);
				    updateScreenShot(screenshot_id, row._id);
				}
			    }
			    else
			    {
				console.log('ERROR : '+ err);
				res.status(500).send('ERROR '+ err + ', please contact support!');
			    }
			});
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


// Functions
function updateScreenShot(screenshot_id, row_id)
{
    db.screenshots.findAndModify({
	query: {
	    _id: screenshot_id
	}, 
	update: {
	    $set: {
		screenshot_images_id: row_id
	    }
	},
	new: true
    }, 
    function(err, doc) 
    {
	if(err)
	{
	    console.log('ERROR '+ err + ', please contact support!');
	}
    });		
}