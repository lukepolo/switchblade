<?php
namespace Modules\Screenshot\Services;

class Screenshots
{
    public function getScreenshot($url, \App\Models\User $user)
    {
        if(getHostByName(getHostName()) != $_SERVER['REMOTE_ADDR'])
        {
            $url = 'http://get.ketchurl.com?url='.$url.'&apikey='.$user->api_key.'&user_id='.$user->id;

            $parsed_url = parse_url($url);

            if(isset($parsed_url['path']) === false)
            {
                $parsed_url['path'] = null;
            }

            $fp = fsockopen($parsed_url['host'], 80, $errno, $errstr, 30);

            $out ="GET ".$parsed_url["path"]."?".$parsed_url['query']." HTTP/1.1\n";
            $out .= "Host: ".$parsed_url['host']."\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            fclose($fp);
	}
    }
}
