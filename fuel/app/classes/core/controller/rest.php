<?php

// TODO - we may need to change *
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization");

class Controller_Rest extends Fuel\Core\Controller_Rest
{
    public static $user_id;
    public static $api_key;
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
                
            static::$user_id = (int) Crypt::decode(static::$api_key);

            if(static::$user_id != 0)
            {
                $api_key = \Auth\Model\Auth_Metadata::query()
                    ->where('user_id', static::$user_id)
                    ->where('key', 'apikey')
                    ->get_one();
                
                // Double check for the apikey
                if($api_key->value == static::$api_key)
                {
                    return true;
                }
            }

            return false;
        }
        else
        {
            return true;
        }
    }
}