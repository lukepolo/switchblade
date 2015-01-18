var express = require('express');
var HttpStatus = require('http-status-codes');
var mongojs = require('mongojs');
// https://github.com/brenden/node-webshot
var webshot = require('webshot');
var fs = require('fs');
var app = express();
 
var hi_options = {
    shotSize: {
        height: 'all'
    },
    quality : 100
};

var options = {
    shotSize: {
        height: 'all'
    }
};

var low_options = {
    shotSize: {
        height: 'all'
    },
    quality: 1
};

var screenshot_folder = __dirname.replace('fuel/packages/nodejs/app','') + 'public/assets/img/screenshots/';

function auth(req, res, next)
{
    console.log('Trying to auth '+ req.query.apikey);
    if(req.query.apikey)
    {
        // Connect to the db
        var uri = "mongodb://127.0.0.1:27017/switchblade_dev",
        db = mongojs.connect(uri, ["users"]);

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
        console.log('No API Key Sent');
        res.status(401).send('Not Authorized');
    }
};

app.use(auth);

app.get('/hi', function(req, res) 
{
    console.log('Trying to create Hi Res '+ req.query.url);
    var path = screenshot_folder + req.query.url+ '.png';
    webshot(req.query.url, path, hi_options, function(err) 
    {
        console.log('Created Hi Res - '+path);
        res.sendFile(path);
    });
});

app.get('/', function(req, res) 
{
    console.log('Trying to create Reg Res '+ req.query.url);
    var path = screenshot_folder + req.query.url+ '.png';
    webshot(req.query.url, path, options, function(err) 
    {
        console.log('Created Reg Res - '+path);
        res.sendFile(path);
    });
});

app.get('/low', function(req, res) 
{
    console.log('Trying to create Low Res '+ req.query.url);
    var path = screenshot_folder + req.query.url+ '.png';
    webshot(req.query.url, path, low_options, function(err) 
    {
        console.log('Created Low Res - '+path);
        res.sendFile(path);
    });

});
 
app.listen(7778);