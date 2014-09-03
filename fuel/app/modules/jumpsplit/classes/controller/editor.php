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

        $url_parsed = parse_url($url);
        $url_host = '//'.$url_parsed['host'];

        $cURL = curl_init($url);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt ($cURL, CURLOPT_CONNECTTIMEOUT, 10);

        // Get the HTML
        $html = curl_exec($cURL);

        // parse out the links jumpcord.com
        $html = preg_replace('/(src)="(\/)(?!\/)(.*)/', '$1="'.$url_host.'/$3', $html);
        
        // currently hack till i fix the REG EXP
        $html = preg_replace('/(href)="(\/)(?!\/)(.*)/', '$1="'.$url_host.'/$3', $html);

        // http://zerosixthree.se/dynamically-change-text-color-with-sass/
        // need to install SASS
        // Add CSS FILE
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

        // Get the status code
        $status = curl_getinfo($cURL, CURLINFO_HTTP_CODE);

        curl_close($cURL);

        echo $html;
        // Required to not use the template
        // TODO - Should i remove not a huge deal to me
        die;
    }
}