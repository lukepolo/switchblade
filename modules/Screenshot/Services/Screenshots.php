<?php

namespace Modules\Screenshot\Services;

class Screenshots
{
    public function make($url, \App\Models\User $user)
    {
	$query_options = [
	    'apikey' => $user->api_key,
	    'url' => $url
	];

	$parsed_url = parse_url(env('SCREENSHOT_CAPTURE_URL').'?'.http_build_query($query_options));

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

    public function get($url, \App\Models\User $user, $options = array())
    {
	$query_options = [
	    'apikey' => $user->api_key,
	    'url' => $url
	];

	foreach($options as $option => $value)
	{
	    $query_options[$option] = $value;
	}

	$url = env('SCREENSHOT_CAPTURE_URL').'?'.http_build_query($query_options);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Fetch image data
	$imageData = curl_exec($ch);

	curl_close($ch);

	// Encode returned data with base64
	return response($imageData)->header('Content-Type', 'image/png');
    }
}
