<?php
namespace HeatMap;

class Controller_Api extends \Controller_Hybrid
{
    public static function get_code()
    {
        // Need to create a mongo heatmap_user to get a unquie ID
        // We can store the users information , like browser ect.
        $mongodb = \Mongo_Db::instance();
       
        $mongodb->where(array(
            'domain' => parse_url($_SERVER['HTTP_REFERER'])['host'],
            'user_id' => \Controller_Rest::$user_id
        ));
        
        $domain = $mongodb->get_one('user_domains');
        
        if(empty($domain) === true)
        {
            // We can group the hosts so we are able to reteieve the info alot faster
            $domain_id = $mongodb->insert('user_domains', array(
                'domain' => parse_url($_SERVER['HTTP_REFERER'])['host'],
                'user_id' => \Controller_Rest::$user_id
            ));
        }
        else
        {
            $domain_id = $domain['_id']->{'$id'};
        }
        
        $parsed_url = parse_url($_SERVER['HTTP_REFERER']);
        
        $url = trim($parsed_url['host'].$parsed_url['path'], '/');
        

        $user_id = $mongodb->insert('heatmap_users', array(
            'domain_id' => $domain_id,
            'url' => $url,
            'user_id' => \Controller_Rest::$user_id,
            'time' => time(),
        ));
        
        // generate user image
        Controller_Api::get_screenshot($url, $user_id);
        
        return array(
            'function' => 'apply_script', 
            'data' => array(
                'url' => \Uri::Create('assets/js/heatmap.min.js'),
                'callback' => "callback = function()
                {
                    var heat_data = new Array();
                    
                    document.querySelector('body').onmousemove = function(ev) 
                    {
                        heat_data.push({
                            x: ev.x + window.scrollX,
                            y: ev.y + window.scrollY,
                            value: 1
                        });
                        
                        if(heat_data.length >= 50)
                        {
                            $.ajax({
                                type: 'POST',
                                url: '".\Uri::Create('heatmap/api/add_heatpoint')."',
                                data: {key:'".\Input::Get('key')."', point_data: heat_data, user: '".$user_id."'},
                            });
                            heat_data = new Array();
                        }
                    };
                };"
            )
        );
    }
    
    public function action_add_heatpoint()
    {      
        // Get an instance
        $mongodb = \Mongo_Db::instance();
       
        $mongodb->where(array(
            'domain' => parse_url($_SERVER['HTTP_REFERER'])['host'],
            'user_id' => \Controller_Rest::$user_id,
        ));
        
        $domain = $mongodb->get_one('user_domains');
        
        $mongodb->insert('heatmap', array(
            'user_id' => \Controller_Rest::$user_id,
            'data' => \Input::POST('point_data'),
            'user' => \Input::POST('user')
        ));
    }
    
   public static function get_screenshot($url, $user_id)
   {       
        if(getHostByName(getHostName()) != $_SERVER['REMOTE_ADDR'])
        {
            $url = 'https://screenshot.switchblade.io/low/?url='.$url.'&apikey='.\Controller_Rest::$api_key.'&user_id='.$user_id;

            $parsed_url = parse_url($url);
            $fp = fsockopen('ssl://'.$parsed_url['host'], 443, $errno, $errstr, 30);

            $out ="GET ".$parsed_url["path"]."?".$parsed_url['query']." HTTP/1.1\n"; 
            $out .= "Host: ".$parsed_url['host']."\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            fclose($fp);
        }
   }
}