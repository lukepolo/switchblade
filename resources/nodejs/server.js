var base_path = __dirname.replace('resources/nodejs', '');
require('dotenv').config({
    path: base_path+'.env'
});
var env = process.env;

var port = env.NODE_SERVER_PORT,
redis = require('redis'),
redis_client = redis.createClient(),
cookie = require('cookie'),
MCrypt = require('mcrypt').MCrypt,
PHPUnserialize = require('php-unserialize'),

// HTTPS
fs = require('fs');

if(env.NODE_HTTPS == 'true') {
    console.log('https');
    var server = require('https').createServer({
        key:    fs.readFileSync(env.SSL_KEY),
        cert:   fs.readFileSync(env.SSL_CERT),
    });
} else {
    var server = require('http').createServer();
}

var io = require('socket.io')(server);

// Maintains a list of timeouts
offline_timeout = {};

// Maintains an active list of users
users = {};
locations = {};

server.listen(port);

// We need to make sure they have a session, otherwise they are not allowed to access node!
io.use(function(socket, next) 
{
    console.log(next);
    // Check to see if the request is coming from our server, via PHP
    if(!socket.request.headers.cookie)
    {
        console.log('From Server');
        next();
    }
    else
    {
        if(typeof socket.request.headers.cookie != 'undefined')
        {
            // Check in redis for the session
            redis_client.get('switchblade:'+decryptCookie(cookie.parse(socket.request.headers.cookie).switchblade_rid), function(error, result)
            {
                if (error)
                {
                    console.log('ERROR');
                    next(new Error(error));
                }
                // They are authorized
                else if (result)
                {
                    console.log('Logged In');
                    next();
                }
                // Not authorized
                else
                {
                    console.log('Not Authorized');
                    next(new Error('Not Authorized'));
                }
            });
        }
        else
        {
            console.log('Not Authorized');
            next(new Error('Not Authorized'));
        }
    }
});

io.on('connection', function (socket)
{
    socket.on('user_info', function (user_info)
    {
        // Clears the timeout for the user
        clearTimeout(offline_timeout[user_info.id]);
        
        if(!users[user_info.id])
        {
            // Set the users name
            socket.user_id = user_info.id;

            users[user_info.id] = {
                socket_info: socket,
                first_name : user_info.first_name,
                last_name : user_info.last_name,
                location: user_info.location
            };
        }
        else
        {
            socket.leave(users[user_info.id].location);
            users[user_info.id].location = user_info.location;
        }
        socket.join(user_info.location);
    });
    
    socket.on('append', function(data)
    {
        user_ids = data[0];
        element = data[1];
        html = data[2];
        callback = data[3];
        
        if (user_ids instanceof Array)
        {
            for(var user_id in user_ids)
            {
                if (users.hasOwnProperty(user_ids[user_id]))
                {
                    users[user_ids[user_id]].socket_info.emit('pull', {
                        element: element,
                        html: html,
                        callback : callback,
                    });
                }
            };
        }
        else
        {
            // Broadcast
        }
    });
    
    
    socket.on('apply_broadcast', function(data)
    {
        io.to(data['location']).emit('apply',
        {
            data: data['data'],
            callback : data['function'],
        });
    });
  
    socket.on('disconnect', function ()
    {
        if (socket.user_id)
        {
            console.log('disconnected ' + socket.user_id);
            // Make them offline after a certain point
            offline_timeout[socket.user_id] = setTimeout(
            function()
            {
                console.log('Deleted User');
                delete users[socket.user_id]
            }, 15000);
        }
    });
});

function decryptCookie(cookie) 
{
    var cookie = JSON.parse(new Buffer(cookie, 'base64'));
    
    var iv = new Buffer(cookie.iv, 'base64');
    var value = new Buffer(cookie.value, 'base64');
    var key = "tQqp^yqBk)xQ_&Q(JKwCmXagJLs)ZO(R";

    var rijCbc = new MCrypt('rijndael-256', 'cbc');
    rijCbc.open(key, iv);

    var decrypted = rijCbc.decrypt(value).toString();
    
    var len = decrypted.length - 1;
    var pad = decrypted.charAt(len).charCodeAt(0);

    var sessionId = PHPUnserialize.unserialize(decrypted.substr(0, decrypted.length - pad));

    return sessionId;
}