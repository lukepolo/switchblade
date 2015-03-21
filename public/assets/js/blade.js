(function() 
{
    var query_data = {};
    var base_url = "https://luke.switchblade.io/";
//    console.log = function() {}
    
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
        
        xhr.onreadystatechange = function() 
        {
            if (xhr.readyState == 4) 
            { 
                if(xhr.responseText && xhr.status == 200)
                {
                    // Apend the JS to the end of the file
                    var data = JSON.parse(xhr.responseText);
                    data.forEach(function(command)
                    {
                        if(command)
                        {
                            // run what we need to get
			    console.log(command.function);
                            try 
                            {
                                swb_fn[command.function].apply(this,command.data);
                            } 
                            catch(e)
                            {
                                // try to call instead
                                try 
                                {
                                    swb_fn[command.function].call(this,command.data);
                                }
                                catch(e)
                                {
                                    document.getElementsByTagName('html')[0].style.visibility= "";
                                    console.log('BAD FUNCTION: '+command.function);
                                    console.log(e);
                                }
                            }
                        }
                    });
                }
                document.getElementsByTagName('html')[0].style.visibility= "";
            }
        }
        
        try
        {
            xhr.send();
        }
        catch(err)
        {
            console.log(err);
            document.getElementsByTagName('html')[0].style.visibility= "";
        }
    }
        
    function EncodeQueryData(data)
    {
        var ret = [];
        for (var d in data)
        {
            ret.push(encodeURIComponent(d) + "=" + encodeURIComponent(data[d]));
        }
       return ret.join("&");
    }
    
    // Watch for the events inside the array and run them
    swb.q.push = function() 
    {    
        run_command(arguments[0]);
    };
    
    function run_command(commands) 
    {
        commands = Array.prototype.slice.call(commands);
        swb_fn[commands[0]].apply(this, commands.slice(1));
    }

    var init = function()
    {
        swb.q.forEach(run_command);
    }
    
    var auth = function(api_key)
    {
        query_data['api_key'] = api_key;
	var xhr = createCORSRequest('GET', "https://luke.switchblade.io/api/v1/mods");
    }
    
    var insert_script = function(script_arguments)
    {
        var script_url = script_arguments.url;
        
        // Adding the script tag to the head as suggested before
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = script_url;

        if(script_arguments.callback)
        {
            var script_callback = eval(script_arguments.callback);
            
            // Then bind the event to the callback function.
            // There are several events for cross browser compatibility.
            script.onreadystatechange = script_callback;
            script.onload = script_callback;
        }
        // Fire the loading
        head.appendChild(script);
    }
    
    var apply_function = function(script_arguments)
    {
        console.log(script_arguments);
	eval(script_arguments.function);
    }
    
     // To quickly send things an optimize the sending we are going to just send an "img"
    // this allows for cross browser and expects no return!
    var send = function(url, params)
    {
        if(params)
        {
            params.api_key = query_data['api_key'];
        }
        else
        {
            params = {
                api_key: query_data['api_key']
            };
        }
        
        url = base_url + url + "?" + serialize(params) + "&ct=img&cb=" + new Date().getTime();
        var img = new Image();
        img.src = url;
    }
    
    function serialize(obj, prefix, depth) 
    {
        if (depth >= 5) 
        {
            return encodeURIComponent(prefix) + "=[RECURSIVE]";
        }
        depth = depth + 1 || 1;

        try 
        {
            if (window.Node && obj instanceof window.Node) 
            {
                return encodeURIComponent(prefix) + "=" + encodeURIComponent(targetToString(obj));
            }
            var str = [];
            for (var p in obj) 
            {
                if (obj.hasOwnProperty(p) && p != null && obj[p] != null) 
                {
                    var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
                    str.push(typeof v === "object" ? serialize(v, k, depth) : encodeURIComponent(k) + "=" + encodeURIComponent(v));
                }
            }
            return str.join("&");
        }
        catch (e) 
        {
            return encodeURIComponent(prefix) + "=" + encodeURIComponent("" + e);
        }
    }
    
    // All Functions Must Be Set Here 
    var swb_fn = 
    {
        init:init,
        auth: auth,
        insert_script: insert_script,
	apply_function: apply_function,
        send: send
    };
    
    init();
})(window);