<?php

namespace ABSplit;

class Controller_Editor extends \Controller_Template
{
    public function action_experiment($experiment_id)
    {
        // We want to use the full width
        $this->template->container = null;
        
        $data = new \stdClass;
        
        $data->experiment = \Model_Absplit_Experiment::query()
            ->where('id', $experiment_id)
            ->where('user_id', \Auth::get('id'))
            ->get_one();
        
        if(empty($data) === false)
        {
            $this->template->content = \View::forge('home/editor', $data);
        }
        else
        {
            \Response::redirect_back(Uri::Create('absplit'));
        }
    }

    // NO param because there is no way to pass a URL easily through FUELPHP
    public function action_url()
    {
        // We grab the URL from the server as FUELPHP parses the forward slasses out of the URL
        $url = urldecode(str_replace('__..__', '.', str_replace('/absplit/editor/url/','', $_SERVER['REQUEST_URI'])));

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
        
        // Correct the URL HOST
        if(preg_match("~\A(http|//)~", $url) == 0) 
        {
            $url = '//'.$url;
        }
               
        $url_parsed = parse_url($url);
        
        if(isset($url_parsed['path']) === false)
        {
            $url_parsed['path'] = null;
        }
        $url_host = '//'.$url_parsed['host'];
        
        $url_parsed['path'] = preg_replace('/[^\/]+\.\w+$/','', $url_parsed['path']);
        // force all relative paths to their own URL
        $body_url = '<base href="'.$url_parsed['scheme'].':'.$url_host.$url_parsed['path'].'">';
        $html = preg_replace('/<head>(.*)<\/head>/s', "<head>$1\n$body_url\n</head>", $html); 
        
        // Fix relative links first
        $html = preg_replace('/<.*(link|script)(.*)(href|src)=("\')(?!http|www)(?!\/\/)(.*?)("\')/i', '<$1$2$3=$4"'.$url_parsed['scheme'].':'.$url_host.$url_parsed['path'].'/$5$6', $html);
        
        // Next we know if their site is http we have to strip their .css files and .js files and replace with our URL
        $html = preg_replace('/<(link|script)(.*)(href|src)=(\'|")(?!\/\/)(.*)(\'|")/i' , '<$1$2$3=$4'.\Uri::Create('absplit/get').'/$5$6', $html);
        
        // Fixing CSS fonts , its only purpose is for that
        $html = preg_replace('/<(link)(.*)(href|src)=(\'|")(\/\/)(.*)(\'|")/i' , '<$1$2$3="'.\Uri::Create('absplit/get').'/$6"', $html);
        
        // http://zerosixthree.se/dynamically-change-text-color-with-sass/
        // need to install SASS
        
        $html = $html."
        <style>
            .absplit-border {
                outline: 3px solid #00CCCC !important;
                outline-offset: -3px !important;
                cursor:pointer !important;
                z-index: 2147483646 !important;
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
    
    public function action_get()
    {
        // We grab the URL from the server as FUELPHP parses the forward slasses out of the URL
        $url = str_replace('/absplit/get/','', $_SERVER['REQUEST_URI']);
        
        try
        {
            $extension = pathinfo($url)['extension'];
        }
        catch(\Exception $e)
        {
            $extension = '';
            // continue;
        }
        
        $cURL = curl_init($url);
        
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt ($cURL, CURLOPT_CONNECTTIMEOUT, 2);
        
        $file = curl_exec($cURL);
        
        $http_status = curl_getinfo($cURL, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($cURL, CURLINFO_CONTENT_TYPE);
        curl_close($cURL);
        
        if($http_status == 200)
        {
            if($contentType == 'text/css')
            {
                $file = preg_replace('/:hover/i','.absplit-hover', $file);
            
// So fucked if we had to update this              
//http://stackoverflow.com/questions/21392684/extracting-urls-from-font-face-by-searching-within-font-face-for-replacement
$pattern = <<<'LOD'
~
(?(DEFINE)
    (?<quoted_content>
        (["']) (?>[^"'\\]++ | \\{2} | \\. | (?!\g{-1})["'] )*+ \g{-1}
    )
    (?<comment> /\* .*? \*/ )
    (?<url_skip> (?: https?: | data: | \.\. ) [^"'\s)}]*+ )
    (?<other_content>
        (?> [^u}/"']++ | \g<quoted_content> | \g<comment>
          | \Bu | u(?!rl\s*+\() | /(?!\*) 
          | \g<url_start> \g<url_skip> ["']?+
        )++
    )
    (?<anchor> \G(?<!^) ["']?+ | @font-face \s*+ { )
    (?<url_start> url\( \s*+ ["']?+ )
)

\g<comment> (*SKIP)(*FAIL) |

\g<anchor> \g<other_content>?+
(?>
    \g<url_start> \K [./]*+  ([^"'\s)}]*+)
  | 
    } (*SKIP)(*FAIL)
)
~xs
LOD;

                // Correct the URL HOST
                if(preg_match("~\A(http|//)~", $url) == 0) 
                {
                    $url = '//'.$url;
                }
                
                $parsed_url = parse_url($url);
                
                // Fix other URLS that are absoulte 
                $file = preg_replace('/url.*\((?:\'|")(\/)(.*)(?:\'|")/i', 'url("'.\Uri::create('absplit/get/').$parsed_url['host'].'/$2"' , $file);
                
                // Also we need to replace all font-face with a custom URL 
                // // Fix HTTP links that wont work for FONTS
                $file = preg_replace($pattern,  \Uri::create('absplit/get/').$parsed_url['host'].preg_replace('/(.*\/).*/i','$1', $parsed_url['path']).'$8' , $file);
                
                // Now we have to fix any imports 
                // https://regex101.com/r/cR9nI3/1
                $file = preg_replace('/@import\s+url\((\'|"|http(?!s))(.*?)[\'"|)]/i', 'import url('.\Uri::create('absplit/get/').'$1$2)', $file);
                
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
}