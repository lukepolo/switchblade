<?php

namespace Jumpsplit;

class Controller_Editor extends \Controller_Template
{
    public function action_index()
    {
        $this->template->title = '';
        $this->template->content = \View::forge('home/editor');
    }

    public function action_url()
    {
        // We grab the URL from the server as FUELPHP parses the forward slasses out of the URL
        $url = urldecode(str_replace('/jumpsplit/editor/url/','', $_SERVER['REQUEST_URI']));

        $cURL = curl_init($url);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt ($cURL, CURLOPT_CONNECTTIMEOUT, 10);

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
        
        $url_parsed = parse_url($url);
        if(isset($url_parsed['host']) === true && empty($url_parsed['host']) === false)
        {
            $url_host = '//'.$url_parsed['host'];
        }
        else
        {
            $url_parsed['scheme'] = 'https';
            $url_host = $url_parsed['path'];
        }
        
        // force all relative paths to their own URL
        $body_url = '<base href="'.$url_parsed['scheme'].':'.$url_host.'">';
        $html = preg_replace('/(<head*>)(.*)(<\/head>)/s', "$1$2$body_url$3", $html); 
        
        if($url_parsed['scheme'] != 'https')
        {
            // Fix relative links first
            $html = preg_replace('/<(link|script)(href|src)=(\'|")(?!http)(?!\/\/)(.*)/', '$2=$3'.$url_parsed['scheme'].':'.$url_host.'/$4$3', $html);
            // Next we know if their site is http we have to strip their .css files and .js files and replace with our URL
            $html = preg_replace('/<(link|script)(.*)(href|src)=(\'|")(?!\/\/)(.*)(\'|")/' , '<$1$2$3="'.\Uri::Create('jumpsplit/get').'/$5"', $html);
        }
        // http://zerosixthree.se/dynamically-change-text-color-with-sass/
        // need to install SASS
        
        $html = $html."
        <style>
            .jumpsplit-border {
                outline: 3px solid #00CCCC !important;
                cursor:pointer !important;
            }
        </style>";

        // ADD JS FILE
        $html = $html."
        <script>
            var selected_element;
            $('body').attr('oncontextmenu', 'return false');
            
            $(document).on('hover','*', function(e)
            {
                if(!$('#jumpsplit-element-menu', window.parent.document).is(':visible'))
                {
                    e.stopPropagation();
                    mouse_x = e.pageX;
                    mouse_y = e.pageY;
                    
                    element = window.top.jumpsplit_get_element(mouse_x, mouse_y)
                    
                    if(element)
                    {
                        $('.jumpsplit-border').removeClass('jumpsplit-border');
                        $(element).addClass('jumpsplit-border');
                    }
                }
            });
            
            // Prevent all links from loading
            $(document).on('click', '*', function(e)
            {
                if($('#jumpsplit-element-menu', window.parent.document).is(':visible'))
                {
                    // Clear out the elements
                    selected_element = null;
                    window.parent.iframe_element = null;
                    $('#jumpsplit-element-menu', window.parent.document).hide();
                }
                e.preventDefault();
                e.stopPropagation();
            });
            
            // Bind new context menu
            $(document).on('mousedown', '*', function(e)
            {
                if(e.which == 3)
                {
                    selected_element = this;
                    $('.jumpsplit-border').removeClass('jumpsplit-border');
                    $(selected_element).addClass('jumpsplit-border');
                    e.preventDefault();
                    e.stopPropagation();
                    window.top.jumpsplit_menu(this);
                    return false; 
                } 
                return true; 
            });
            
            $(window).on('resize scroll', function()
            {
                if(selected_element)
                {
                    window.top.jumpsplit_widget_positions(element);
                }
            });
        
        </script>";

        curl_close($cURL);

        echo $html;
        // Required to not use the template
        // TODO - Should i remove not a huge deal to me
        die;
    }
    
    public function action_get()
    {
        echo false;
        // We grab the URL from the server as FUELPHP parses the forward slasses out of the URL
        $url = urldecode(str_replace('/jumpsplit/get/','', $_SERVER['REQUEST_URI']));
        
        $extension = pathinfo($url)['extension'];
        
        $cURL = curl_init($url);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt ($cURL, CURLOPT_CONNECTTIMEOUT, 2);
        
        
        $file = curl_exec($cURL);
        
        curl_close($cURL);
        
        switch($extension)
        {
            case 'css' : 
                header("Content-type: text/css", true);
            break;
            case 'js':
                header('Content-Type: application/javascript', true);
            break;
            case 'atom':
                header('Content-Type: application/atom+xml');
            break;
            case 'pdf':
                header('Content-Type: application/pdf');
            break;
            case 'rss':
                header('Content-Type: application/rss+xml; charset=ISO-8859-1');
            break;
            case 'xml':
                header('Content-Type: text/xml');
            break;
        }
        echo $file;
        die;
    }
}