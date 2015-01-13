(function() 
{
    var query_data = {};
    
    // http://www.html5rocks.com/en/tutorials/cors/
    function createCORSRequest(method, url) 
    {
        url = url+"?"+EncodeQueryData(query_data);
        var xhr = new XMLHttpRequest();
        if ("withCredentials" in xhr) 
        {
            // Check if the XMLHttpRequest object has a "withCredentials" property.
            // "withCredentials" only exists on XMLHTTPRequest2 objects.
            xhr.open(method, url, true);
        } 
        else if (typeof XDomainRequest != "undefined")
        {
            // XDomainRequest only exists in IE, and is IE's way of making CORS requests.
            xhr = new XDomainRequest();
            xhr.open(method, url);
        }
        else 
        {
            // Otherwise, CORS is not supported by the browser.
            xhr =  null;
            throw new Error('CORS not supported');
        }
        return xhr;
    }
        
    // Watch for the events inside the array and run them
    swb.q.push = function() 
    {    
        run_command(arguments[0]);
    };
    
    function EncodeQueryData(data)
    {
       var ret = [];
       for (var d in data)
          ret.push(encodeURIComponent(d) + "=" + encodeURIComponent(data[d]));
       return ret.join("&");
    }
    
    function run_command(commands) 
    {
        commands = Array.prototype.slice.call(commands);
        swb_fn[commands[0]].apply(this, commands.slice(1));
    }

    var init = function()
    {
        // Load any extra JS files here 
        // EX : jquery if they dont have
        swb.q.forEach(run_command);
    }
    
    var auth = function(key)
    {
        query_data['key'] = key;
    }
    
    // Gets all nesscary JS files & Appends Them
    var get_mods = function()
    {
        var xhr = createCORSRequest('GET', "https://luke.switchblade.io/api/mods.json");
        
        if(xhr)
        {
            xhr.send();
            xhr.onreadystatechange = function() 
            {
                if (xhr.readyState == 4) 
                { 
                    if(xhr.responseText)
                    {
                        // Apend the JS to the end of the file
                        var data = JSON.parse(xhr.responseText);

                        data.forEach(function(data)
                        {
                            eval(data);
                        });
                    }
                    document.getElementsByTagName('html')[0].style.visibility= "";
                }
            }
        }
    }
    
    // All Functions Must Be Set Here 
    var swb_fn = 
    {
        init:init,
        auth: auth,
        get_mods: get_mods,
    };
    init();
})(window);