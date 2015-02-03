<?php
namespace Analytics;

class Controller_Api extends \Controller_Hybrid
{
    public function action_pageview()
    {      
        $mongodb = \Mongo_Db::instance();
       
        $mongodb->where(array(
            'user_id' => \Controller_Rest::$user_id,
            'domain' => parse_url($_SERVER['HTTP_REFERER'])['host']
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
        
        $mongodb->insert('analytics', array(
            'domain_id' => $domain_id,
            'url' => $_SERVER['HTTP_REFERER'],
            'user_id' => \Controller_Rest::$user_id,
            'time' => time(),
        ));
    }
}