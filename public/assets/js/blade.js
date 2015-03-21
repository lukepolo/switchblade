(function() 
{
    var query_data = {};
    
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
                            console.log(command.data);
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
    
    var auth = function(key)
    {
        query_data['key'] = key;
	var xhr = createCORSRequest('GET', "https://luke.switchblade.io/api/v1/mods");
    }
    
    var pageview = function()
    {
        var xhr = createCORSRequest('GET', "https://luke.switchblade.io/api/v1/pageview");
    }
    
    var absplit = function(data)
    {
        data.forEach(function(data)
        {
            eval(data);
        });
    }
    
    var apply_script = function(script_arguments)
    {
        var script_url = script_arguments.url;
        var script_callback = eval(script_arguments.callback);
	
        // Adding the script tag to the head as suggested before
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = script_url;

        // Then bind the event to the callback function.
        // There are several events for cross browser compatibility.
        script.onreadystatechange = script_callback;
        script.onload = script_callback;

        // Fire the loading
        head.appendChild(script);
    }
    
    var apply_function = function(script_arguments)
    {
	eval(script_arguments.function);
    }
    // All Functions Must Be Set Here 
    var swb_fn = 
    {
        init:init,
        auth: auth,
        pageview: pageview,
        absplit: absplit,
        apply_script: apply_script,
	apply_function: apply_function
    };
    
    init();
})(window);