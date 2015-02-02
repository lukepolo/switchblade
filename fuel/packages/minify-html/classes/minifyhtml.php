<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Luke Policinski
 * @license    MIT License
 * @copyright  2015
 * @link       http://lukepolo.com
 */

namespace MinifyHTML;

class MinifyHTML
{
    protected static $_defaults;
    
    public static $html;
    
    public static $saveJS;
    public static $savePreText;
    
    public static function _init()
    {
        \Config::load('minify-html', true);
        static::$_defaults = \Config::get('minify-html');
    }
    
    /**
    * Compress the HTML output
    *
    * @param string $html the contents of the view file
    *
    * @return string
    */
    public static function compileMinify($html)
    {
        static::$html = $html;
        static::$saveJS = array();
        if (static::$_defaults['minify']) 
        {
            // We want to store all the exceptions so we can replace them after we the minfiication
            MinifyHTML::storeExceptions();
            
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/"                  => '<?php ',
                "/\n([\S])/"                => ' $1',
                "/\r/"                      => '',
                "/\n/"                      => '',
                "/\t/"                      => ' ',
                "/ +/"                      => ' ',
            );
            
            static::$html = preg_replace(array_keys($replace), array_values($replace), static::$html);
            
            // Replace the exceptions
            MinifyHTML::replaceExceptions();
        } 
        
        return static::$html;
    }
    
    public static function storeExceptions()
    {
        MinifyHTML::storeJS();
        MinifyHTML::storePreText();
    }
    
    public static function replaceExceptions()
    {
        MinifyHTML::restoreJS();
        MinifyHTML::restorePreText();
    }
    
    public static function storeJS()
    {
        // Store ALL JavaScript Functions 
        preg_match_all('/<script\b[^>]*>(.*?)<\/script>/s', static::$html, $js_matches);
        if(empty($js_matches[1]) === false)
        {
            
            foreach($js_matches[1] as $match)
            {
                if(empty($match) === false)
                {
                    if(\Package::loaded('fuel-casset'))
                    {
                        $match = \Casset\Casset_JSMin::minify($match);
                    }
                    // MINIFY IF THEY HAVE CASSET
                    static::$saveJS[] = $match;
                }
            }
           
            static::$html = preg_replace_callback('/(<script\b[^>]*>)(.*?)(<\/script>)/s',
                function($matches) 
                {
                    if(empty($matches[2]) === false)
                    {
                        return $matches[1].'[minify_js]'.$matches[3];
                    }
                    else
                    {
                        return $matches[1].$matches[3];
                    }
                },
                static::$html
            );
        }
    }
    
    public static function restoreJS()
    {
        $i = 0;
        static::$html = preg_replace_callback('/(\[minify_js\])/',
            function($matches) use (&$i)
            {
                return static::$saveJS[$i++];
            },
            static::$html
        );
    }
    
    public static function storePreText()
    {
        // Store ALL JavaScript Functions 
        preg_match_all('/<(?:pre|textarea)\b[^>]*>(.*?)<\/(?:pre|textarea)>/s', static::$html, $pre_text_matches);
        
        if(empty($pre_text_matches[1]) === false)
        {
            foreach($pre_text_matches[1] as $match)
            {
                if(empty($match) === false)
                {
                    static::$savePreText[] = $match;
                }
            }
           
            static::$html = preg_replace_callback('/(<(?:pre|textarea)\b[^>]*>)(.*?)(<\/(?:pre|textarea)>)/s',
                function($matches) 
                {
                    if(empty($matches[2]) === false)
                    {
                        return $matches[1].'[pre_text]'.$matches[3];
                    }
                    else
                    {
                        return $matches[1].$matches[3];
                    }
                },
                static::$html
            );
        }
    }
    
    public static function restorePreText()
    {
        $i = 0;
        static::$html = preg_replace_callback('/(\[pre_text\])/',
            function($matches) use (&$i)
            {
                return static::$savePreText[$i++];
            },
            static::$html
        );
    }
}