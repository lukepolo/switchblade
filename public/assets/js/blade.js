!function(){function createCORSRequest(e,t){t=t+"?"+EncodeQueryData(query_data);var n=new XMLHttpRequest;if("withCredentials"in n)n.open(e,t,!0);else{if("undefined"==typeof XDomainRequest)throw n=null,new Error("CORS not supported");n=new XDomainRequest,n.open(e,t)}n.onreadystatechange=function(){if(4==n.readyState){if(n.responseText&&200==n.status){var e=JSON.parse(n.responseText);e.forEach(function(e){if(e)try{swb_fn[e["function"]].apply(this,e.data)}catch(t){try{swb_fn[e["function"]].call(this,e.data)}catch(t){document.getElementsByTagName("html")[0].style.visibility="",console.log("BAD FUNCTION: "+e["function"]),console.log(t)}}})}document.getElementsByTagName("html")[0].style.visibility=""}};try{n.send()}catch(a){console.log(a)}}function EncodeQueryData(e){var t=[];for(var n in e)t.push(encodeURIComponent(n)+"="+encodeURIComponent(e[n]));return t.join("&")}function run_command(e){e=Array.prototype.slice.call(e),swb_fn[e[0]].apply(this,e.slice(1))}function serialize(e,t,n){if(n>=5)return encodeURIComponent(t)+"=[RECURSIVE]";n=n+1||1;try{if(window.Node&&e instanceof window.Node)return encodeURIComponent(t)+"="+encodeURIComponent(targetToString(e));var a=[];for(var r in e)if(e.hasOwnProperty(r)&&null!=r&&null!=e[r]){var i=t?t+"["+r+"]":r,o=e[r];a.push("object"==typeof o?serialize(o,i,n):encodeURIComponent(i)+"="+encodeURIComponent(o))}return a.join("&")}catch(c){return encodeURIComponent(t)+"="+encodeURIComponent(""+c)}}var modules={},query_data={},base_url="https://switchblade.lukepolo.com/";swb.q.push=function(){run_command(arguments[0])};var init=function(){swb.q.forEach(run_command)},auth=function(e){query_data.api_key=e;createCORSRequest("GET",base_url+"api/v1/mods")},insert_script=function(script_arguments){if(!modules[script_arguments.module]){swb("register",script_arguments.module);var script_url=script_arguments.url,head=document.getElementsByTagName("head")[0],script=document.createElement("script");if(script.type="text/javascript",script.src=script_url,script_arguments.callback){var script_callback=eval(script_arguments.callback);script.onreadystatechange=script_callback,script.onload=script_callback}head.appendChild(script)}},apply_function=function(script_arguments){eval(script_arguments["function"])},send=function(e,t){t?t.api_key=query_data.api_key:t={api_key:query_data.api_key},e=base_url+e+"?"+serialize(t)+"&ct=img&cb="+(new Date).getTime();var n=new Image;n.src=e},register=function(e){modules[e]=!0},swb_fn={init:init,auth:auth,insert_script:insert_script,apply_function:apply_function,send:send,register:register};init()}(window);