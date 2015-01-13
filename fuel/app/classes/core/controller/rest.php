<?php

// TODO - we may need to change *
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization");

class Controller_Rest extends Fuel\Core\Controller_Rest
{
    public static $user_id;
    // TODO - look into securing it more
    // http://www.sitepoint.com/using-json-web-tokens-node-js/
    protected function _api_auth()
    {
        if(\Auth::Check() === false)
        {
            static::$user_id = (int) Crypt::decode(Input::get('key'));

            if(is_int(static::$user_id))
            {
                $api_key = \Auth\Model\Auth_Metadata::query()
                    ->where('user_id', static::$user_id)
                    ->where('key', 'apikey')
                    ->get_one();
                
                // Double check for the apikey
                if($api_key->value == Input::get('key'))
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