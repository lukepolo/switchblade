<?php
namespace Fuel\Tasks;

class Mongo
{

    public function create_indexes()
    {
        $mongodb = \Mongo_Db::instance();
        // GLOBALS
        
        // USER UNIQUE
        $mongodb->add_index('user_domains', array(
            'user_id' => 1,
        ),array(
            'unique' => true
        ));
        
        // BY Users and Api Key
        $mongodb->add_index('user_domains', array(
            'user_id' => 1,
            'api_key' => 1
        ));
        
        // UNIQUE
        $mongodb->add_index('user_domains', array(
            'user_id' => 1,
            'domain' => 1
        ), array(
            'unique' => true
        ));
        
        // Screenshots API / Module
        // SCREENSHOTS UNIQUE
        $mongodb->add_index('screenshots', array(
            'checksum' => 1,
        ));
        
        // BY ID
        $mongodb->add_index('screenshots', array(
            '_id' => 1,
        ));
        
        // HEAT MAP MODULE
        $mongodb->add_index('heatmap_users', array(
            'user_id' => 1,
        ));
         
        $mongodb->add_index('heatmap', array(
            'user_id' => 1,
            'user' => 1
        ));
        
        // ANALYTICS MODULE
        $mongodb->add_index('analytics', array(
            'user_id' => 1,
            'domain' => 1
        ));
         
    }
}
