var express = require('express');
var HttpStatus = require('http-status-codes');
var mongojs = require('mongojs');
var crypto = require('crypto');
var webshot = require('webshot');
var fs = require('fs');
var app = express();
var db = mongojs.connect("mongodb://127.0.0.1:27017/switchblade_dev", ["users", "screenshots"]);

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
    console.log('Trying to create '+ url);
    db.screenshots.insert({url: url, user_id: user_id, checksum: null, image_path: null, api_key : api_key}, function(err, row)
    { 
        if(!err)
        {
            image_path = row._id;
            var path = screenshot_folder + image_path + '.jpg';
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
                            if(!err)
                            {
                                if(records != null)
                                {
                                    image_path = records._id;
                                    console.log('Deleteing '+ image_path);
                                    fs.unlink(path, function(err)
                                    {
                                        if(err)
                                        {
                                            console.log('ERROR '+ err + ', please contact support!');
                                        }
                                    });
                                }
                                
                                db.screenshots.findAndModify({
                                    query: {
                                        _id: row._id
                                    }, 
                                    update: { $set: {checksum: sum,  image_path: image_path}},
                                    new: true
                                }, function(err, doc) 
                                {
                                    console.log(doc);
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
