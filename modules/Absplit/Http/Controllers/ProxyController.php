<?php namespace Modules\Absplit\Http\Controllers;

use \App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Modules\Absplit\Models\Absplit_Experiments;

class ProxyController extends Controller
{
    public $asset_url = '\Modules\Absplit\Http\Controllers\ProxyController@getAsset';

    public function getIndex()
    {
	$experiment = Absplit_Experiments::find(\Request::input('id'));
	$url = $experiment->url;

	$cURL = curl_init($url);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($cURL, CURLOPT_TIMEOUT, 3);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);

        // Store their session , it may be needed for later
        curl_setopt($cURL, CURLOPT_COOKIEJAR, "/tmp/switchblade.txt" );
        curl_setopt($cURL, CURLOPT_COOKIEFILE, "/tmp/switchblade.txt");

        // Get the HTML
        $html = curl_exec($cURL);

        // Get the status code
        $status = curl_getinfo($cURL, CURLINFO_HTTP_CODE);
        if($status != 200)
        {
            $parsed_url = parse_url($url);
            curl_setopt($cURL, CURLOPT_URL, $parsed_url['host']);
            $html = curl_exec($cURL);
        }

        $url = curl_getinfo($cURL, CURLINFO_EFFECTIVE_URL);

	$asset_url = action($this->asset_url);
        // Correct the URL HOST
        if(preg_match("~\A(http|//)~", $url) == 0)
        {
            $url = '//'.$url;
        }

        $url_parsed = parse_url($url);

	if($url_parsed['scheme'] != 'https')
	{
	    $url_parsed = parse_url($this->getCheckSSL($url));
	}

        if(isset($url_parsed['path']) === false)
        {
            $url_parsed['path'] = null;
        }
        $url_host = '//'.$url_parsed['host'];

        // TODO - remove SWB Scripts

        // TODO - IF THE URL IS HTTP MAKE SURE TO USE HTTPS THROUGH SWITCHBLADE
        $url_parsed['path'] = preg_replace('/[^\/]+\.\w+$/','', $url_parsed['path']);

        // force all relative paths to their own URL
        $body_url = '<base href="'.$url_parsed['scheme'].':'.$url_host.$url_parsed['path'].'">';

        $body_url = $body_url."
        <script>
            var open = XMLHttpRequest.prototype.open;

            XMLHttpRequest.prototype.open = function()
            {
                var pattern = /^((http|https|ftp):\/\/)/;
                if(pattern.test(arguments[1]) == false)
                {
                    arguments[1] = '".$asset_url."?url=".$url_parsed['scheme'].':'.$url_host.$url_parsed['path']."' + arguments[1];
                }
                open.apply(this, arguments);
                console.log(' NOW TO SEND');
            }
        </script>";

        $html = preg_replace('/<head>(.*)<\/head>/s', "<head>$1\n$body_url\n</head>", $html);

        // Fix relative links first
        $html = preg_replace('/<(link|script)(.*)(href|src)=(\'|")(?!http|www)(?!\/\/)(.*?)(\'|")/i', '<$1$2$3=$4'.$url_parsed['scheme'].':'.$url_host.$url_parsed['path'].'/$5$6', $html);

        // Fix relative css links
        // https://regex101.com/r/zO2aG9/1
        $html = preg_replace('/url\((?!\/\/)(\/|\'\/|"\/)(.*?)(\'|"|\))/i', 'url('.$url_parsed['scheme'].':'.$url_host.$url_parsed['path'].'$1$2$3', $html);

	// Fix any // that should render as HTTPS since they already opted into that case
        $html = preg_replace('/<(link|script)(.*)(href|src)=(\'|")(\/\/)(.*?)(\'|")/i' , '<$1$2$3=$4https://$6$7', $html);

	// Strip their .css files and .js files and replace with our URL
	$html = preg_replace('/<(link|script)(.*)(href|src)=(\'|")(?!\/\/)(.*)(\'|")/i' , '<$1$2$3=$4'.$asset_url.'?url=$5$6', $html);

	$html = $html."
        <style>
            .absplit-border {
                outline: 3px solid #00CCCC !important;
                outline-offset: -3px !important;
                cursor:pointer !important;
            }

            .absplit_secondary_border {
                outline: 3px solid red !important;
                outline-offset: -3px !important;
                cursor:pointer !important;
                z-index: 2147483646 !important;
            }

            .ui-draggable{cursor:move ;}
            .ui-draggable-disabled {cursor:default;}
            .ui-resizable-handle {border: 1px solid; opacity: 0.3; width:7px; height:7px;background-color: #FFFFFF;}
            .ui-resizable-n, .ui-resizable-s {width:7px; height:7px;left:50%;}
            .ui-resizable-e, .ui-resizable-w {width:7px; height:7px;top:50%; }
            .ui-resizable-se{ background-image: none;bottom: -5px; right: -5px; z-index: 1002;}

            .ui-resizeable-overlay {
                background-color: grey;
                position: absolute;
                top: 0;
                right: 0;
                left: 0;
                bottom: 0;
                opacity: .5;
            }
        </style>";

        // TODO - ADD JQUERY BUT NO COCONFLICT VERSION!
        // ADD JS FILE

        $html = $html.'

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>var $SWB = jQuery.noConflict(true);</script>

        <script>

            var selected_element;
            $SWB("body").attr("oncontextmenu", "return false");

            function add_absplit_border(element)
            {
                $SWB(".absplit-border").removeClass("absplit-border");
                $SWB(element).addClass("absplit-border");
            }

            function add_absplit_secondary_border(element)
            {
                $SWB(".absplit_secondary_border").removeClass("absplit_secondary_border");
                $SWB(element).addClass("absplit_secondary_border");
            }

            $SWB(document).on("mouseover","*", function(e)
            {
                if(!$SWB("#original", window.parent.document).hasClass("active") && $SWB(".ui-resizable").length == 0)
                {
                    $SWB(this).addClass("absplit-hover");

                    // Alot of times its the actual parent that needs the hovering
                    $SWB(this).parent().addClass("absplit-hover");
                    if(!$SWB("#absplit-element-menu, body .ui-draggable-dragging, .drag, body.resize", window.parent.document).is(":visible"))
                    {
                        e.stopPropagation();
                        mouse_x = e.pageX;
                        mouse_y = e.pageY;

                        element = window.top.absplit_get_element(mouse_x, mouse_y);

                        if(element)
                        {
                            add_absplit_border(element);
                        }
                    }
                }
            });

            $SWB(document).on("mouseover","body.absplit_swap *, body.absplit_moveto *", function(e)
            {
                e.stopPropagation();
                mouse_x = e.pageX;
                mouse_y = e.pageY;

                element = window.top.absplit_get_element(mouse_x, mouse_y);

                if(element)
                {
                    add_absplit_secondary_border(element);
                }
            });

            // Prevent all links from loading
            $SWB(document).on("click", "*", function(e)
            {
                if(!$SWB("#original", window.parent.document).hasClass("active") && $SWB(".ui-resizable").length == 0)
                {
                    if($SWB("#absplit-element-menu", window.parent.document).is(":visible"))
                    {
                        // Clear out the elements
                        selected_element = null;
                        window.parent.iframe_element = null;
                        $SWB("#absplit-element-menu", window.parent.document).hide();
                    }
                }
                e.preventDefault();
                e.stopPropagation();
            });

            $SWB(document).on("mouseleave", "*", function(e)
            {
                if($SWB(this).hasClass("absplit-locked") === false)
                {
                    $SWB(this).removeClass("absplit-hover");
                }
            });

            // Bind new context menu
            $SWB(document).on("mousedown", "*", function(e)
            {
                if(!$SWB("#original", window.parent.document).hasClass("active") && $SWB(".ui-resizable").length == 0)
                {
                    if(e.which == 3)
                    {
                        selected_element = this;
                        $SWB(".absplit-border").removeClass("absplit-border"); // this really needed?
                        $SWB(selected_element).addClass("absplit-border");
                        e.preventDefault();
                        e.stopPropagation();
                        window.top.absplit_menu(this);
                        return false;
                    }
                    return true;
                }
            });

            $SWB(window).on("resize scroll", function()
            {
                if(selected_element)
                {
                    window.top.absplit_widget_menu_position(element);
                }
            });
        </script>';

        curl_close($cURL);

        echo $html;

        exit();
    }

    public function getAsset()
    {
	$url = \Request::input('url');
	$asset_url = action($this->asset_url);

	$cURL = curl_init($url);

        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($cURL, CURLOPT_HEADER, 0);
        curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($cURL, CURLOPT_TIMEOUT, 3);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);

        $file = curl_exec($cURL);


        $http_status = curl_getinfo($cURL, CURLINFO_HTTP_CODE);

        $contentType = curl_getinfo($cURL, CURLINFO_CONTENT_TYPE);
        curl_close($cURL);

        if($http_status == 200)
        {
            if($contentType == 'text/css')
            {
                // Replacing the hover case into a clas so we can use it on their site
                $file = preg_replace('/:hover/i','.absplit-hover', $file);

                // Correct the URL HOST
                if(preg_match("~\A(http|//)~", $url) == 0)
                {
                    $url = '//'.$url;
                }

                $parsed_url = parse_url($url);

                // Remove the quotes from the URL's as they are optional , which allows correcting much easier
                // https://www.regex101.com/r/iU3kZ0/2
                $file = preg_replace('/url\((\'|")(.*?)(\'|")/', 'url($2', $file);

                // Fix things that have no begining slash by adding the path!
                $real_path = preg_replace('/(.*\/).*/i','$1', $parsed_url['path']);

                // Fix urls with relative paths
                // https://www.regex101.com/r/eS7gP8/14
                $file = preg_replace('/url\((?:(\/|\.\.|[a-zA-Z]))(?!\/h|\/w)(.*?)(?:\))/i',  'url('.$asset_url.'?url='.$parsed_url['host'].$real_path.'$1$2)' , $file);
            }
            header('Content-Type: '.$contentType);
            echo $file;
        }
        else
        {
            header("HTTP/1.1 404 Not Found");
        }
        exit();
    }

    public function getCheckSSL($url)
    {
	$cURL = curl_init('https://'.parse_url($url)['host']);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 0);
	curl_setopt($cURL, CURLOPT_NOBODY, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, 1);

        $html = curl_exec($cURL);

        // Get the status code
        $status = curl_getinfo($cURL, CURLINFO_HTTP_CODE);

        if($status == 200)
        {
	    return curl_getinfo($cURL, CURLINFO_EFFECTIVE_URL);
	}
	else
	{
	    return false;
	}
    }
}