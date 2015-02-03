<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization");

class Controller_Rest extends Fuel\Core\Controller_Rest
{
    public static $user_id;
    public static $api_key;
    public static $mods;
    
    protected function _api_auth()
    {
        if(\Auth::Check() === false)
        {
            if(Input::get('key'))
            {
                static::$api_key = Input::get('key');
            }
            else
            {
                static::$api_key = Input::post('key');
            }
                
            $mongodb = \Mongo_Db::instance();
            
            $user = $mongodb->where(array(
                    'api_key' => static::$api_key
                ))
                ->get_one('users');
            
            if(isset($user['user_id']) === true)
            {
                static::$user_id = $user['user_id'];
                static::$mods = $user['mods'];
                return true;
            }

            return false;
        }
        else
        {
            return true;
        }
    }
}