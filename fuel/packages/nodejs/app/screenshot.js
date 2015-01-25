var express = require('express');
var HttpStatus = require('http-status-codes');
var mongojs = require('mongojs');
var crypto = require('crypto');
var webshot = require('webshot');
var fs = require('fs');
var app = express();
var db = mongojs.connect("mongodb://127.0.0.1:27017/switchblade_dev", ["users", "screenshots"]);

var delay = 0;

var hi_options = {
    shotSize: {
        height: 'all'
    },
    quality : 100,
    renderDelay: delay
};

var options = {
    shotSize: {
        height: 'all',
        quality : 85
    },
    renderDelay: delay 
};

var low_options = {
    shotSize: {
        height: 'all',
    },
    quality: 50,
    renderDelay: delay
};

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

function get_screenshot(url, options, res, user_id)
{
    console.log('Trying to create '+ url);
    console.log(user_id);
    db.screenshots.insert({url: url, user_id: user_id, checksum: null, image_path: null}, function(err, screenshot_id)
    { 
        if(!err)
        {
            screenshot_id = screenshot_id._id;
            var path = screenshot_folder + screenshot_id + '.jpg';
            webshot(url, path, options, function(err) 
            {
                console.log('Created Reg Res - '+path);
                res.sendFile(path);
                
                // lets check to make sure the image is unquie...otherwise its a waste of space
                fs.readFile(path, function (err, data)
                {
                    var sum =  crypto.createHash('md5').update(data, 'utf8').digest('hex');
                    if(!err)
                    {
                        db.screenshots.findOne({checksum: sum}, function(err, records)
                        {
                            console.log(records)
                            if(!err)
                            {
                                if(records != null)
                                {
                                    console.log('Deleteing '+ screenshot_id);
                                    screenshot_id = records._id;
                                    fs.unlink(path, function(err)
                                    {
                                        if(err)
                                        {
                                            console.log('ERROR '+ err + ', please contact support!');
                                        }
                                    });
                                }
                                
                                db.screenshots.update({user_id: user_id, url: url}, {url: url, user_id: user_id, checksum: sum,  image_path: screenshot_id}, function(err) 
                                {
                                    if(err)
                                    {
                                        console.log('ERROR '+ err + ', please contact support!');
                                    }
                                });
                                
                            }
                            else
                            {
                                console.log('ERROR '+ err + ', please contact support!');
                            }
                        });
                    }
                    else
                    {
                        console.log('ERROR '+ err + ', please contact support!');
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

app.use(auth);

app.get('/hi', function(req, res) 
{
    get_screenshot(req.query.url, hi_options, res, req.query.user_id);
});

app.get('/', function(req, res) 
{
    get_screenshot(req.query.url, options, res, req.query.user_id);
});

app.get('/low', function(req, res) 
{
    get_screenshot(req.query.url, low_options, res, req.query.user_id);

});
 
app.listen(7778);
