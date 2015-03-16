<?php

namespace Modules\Screenshot\Services;

class Screenshots
{
    public function make($url, \App\Models\User $user)
    {
	$query_data = http_build_query([
	    'apikey' => $user->api_key,
	    'url' => $url
	]);
	$parsed_url = parse_url('http://get.ketchurl.com?'.$query_data);

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
