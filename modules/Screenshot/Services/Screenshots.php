<?php

namespace Modules\Screenshot\Services;

class Screenshots
{
    public function make($url, \App\Models\User $user)
    {
	$parsed_url = parse_url($url);

	if(isset($parsed_url['path']) === false)
	{
	    $parsed_url['path'] = null;
	}

	$url = 'http://get.ketchurl.com?apikey='.$user->api_key.'&url='.trim($parsed_url['host'].$parsed_url['path'], '/');

	$parsed_url = parse_url($url);

	if(isset($parsed_url['path']) === false)
	{
	    $parsed_url['path'] = '/';
	}

	$fp = fsockopen($parsed_url['host'], 80, $errno, $errstr, 30);
	stream_set_blocking($fp, 1);

	$out ="GET ".$parsed_url["path"]."?".$parsed_url['query']." HTTP/1.1\r\n";
	$out .= "Host: ".$parsed_url['host']."\r\n";
	$out .= "Connection: Close\r\n\r\n";

	fwrite($fp, $out);
	fclose($fp);
    }
}
