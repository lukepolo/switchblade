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

//app.use(auth);

app.get('/hi/:url', function(req, res) 
{
    var path = '/var/www/html/dev/switchblade/public/assets/img/screenshots/'+ req.params.url+ '.png';
    webshot(req.params.url, path, hi_options, function(err) 
    {
        console.log('Hi Res - '+path);
        res.sendFile(path);
    });

});

app.get('/:url', function(req, res) 
{
    console.log('Trying to create Reg Res '+ req.params.url);
    var path = '/var/www/html/dev/switchblade/public/assets/img/screenshots/'+ req.params.url+ '.png';
    
    try
    {
        webshot(req.params.url, path, options, function(err) 
        {
            console.log('Reg Res - '+path);
            res.sendFile(path);
        });
    }
    catch(err)
    {
        console.log(err);
        res.status(500).send('ERROR '+ err + ', please contact support!');
    }
});

app.get('/low/:url', function(req, res) 
{
    var path = '/var/www/html/dev/switchblade/public/assets/img/screenshots/'+ req.params.url+ '.png';
    webshot(req.params.url, path, low_options, function(err) 
    {
        console.log('Low Res - '+path);
        res.sendFile(path);
    });

});
 
app.listen(7778);