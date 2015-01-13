<?php

// TODO - we may need to change *
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization");

class Controller_Rest extends Fuel\Core\Controller_Rest
{
    // TODO - look into securing it more
    // http://www.sitepoint.com/using-json-web-tokens-node-js/
    protected function _api_auth()
    {
        $user_id = (int) Crypt::decode(Input::get('key'));

        if(is_int($user_id))
        {
            \Auth::force_login($user_id);
            
            // Double check for the apikey
            if(\Auth::get('apikey') == Input::get('key'))
            {
                return true;
            }
        }

        return false;
    }
}