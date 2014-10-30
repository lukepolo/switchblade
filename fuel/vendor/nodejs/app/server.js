var unserialize = require('./unserialize.js');
var memcache = require('memcache');
var cookie = require('cookie');
var os = require('os')

// HTTPS
var https = require('https');
fs = require('fs');
var options = {
    key:    fs.readFileSync('/etc/ssl/switchblade.key'),
    cert:   fs.readFileSync('/etc/ssl/switchblade.crt'),
    ca:     fs.readFileSync('/etc/ssl/COMODORSADomainValidationSecureServerCA.crt')
};

var server = require('https').createServer(options);
var io = require('socket.io')(server);

server.listen(7777);

// Maintains a list of timeouts
var offline_timeout = {};

// Maintains an active list of users
var users = {};

// We need to make sure they have a session, otherwise they are not allowed to access node!
io.use(function(socket, next) 
{
    // Get all the interfaces
    var interfaces = os.networkInterfaces();
    var addresses = [];
    // Go through all the interfaces
    for (k in interfaces) {
        for (k2 in interfaces[k]) {
            var address = interfaces[k][k2];
            // Check to see if we have an IPv4 address
            if (address.family == 'IPv4' && !address.internal) {
                // Record the address
                addresses.push(address.address)
            }
        }
    }
  
    // Check to see if the request is coming from our server, via PHP
    if(addresses.indexOf(socket.request.connection.remoteAddress) >= 0)
    {
        next();
    }
    else
    {
        // Get the cookies from the headers
        var cookies = cookie.parse(socket.request.headers.cookie);
        
        var client = new memcache.Client(11211, "127.0.0.1");
        client.connect();
        
        var session = unserialize.convert(cookies.fuelmid);
        console.log('Checking');
        // Check in the memcache for the session
        client.get("fuelmid_"+session[0], function(error, result)
        {
            if (error)
            {
                console.log('ERROR');
                next(new Error(error));
            }
            // They are authorized
            else if (result)
            {
                console.log('LOGGED IN!');
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
});

io.on('connection', function (socket)
{
    socket.on('user_info', function (user_info)
    {
        // Clears the timeout for the user
        clearTimeout(offline_timeout[user_info.id]);
        
        // Set the users name
        socket.user_id = user_info.id;
        
        users[user_info.id] = {
            socket_info: socket,
            user_info : user_info
        };
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
            if (user_ids == null)
            {
                console.log('broadcast');
            }
            else
            {
                if (users.hasOwnProperty(user_ids))
                {
                    users[user_ids].socket_info.emit('pull', {
                        element: element,
                        html: html,
                        callback : callback,
                    });
                }
            }
        }
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