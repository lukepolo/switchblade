<?php

namespace Modules\Heatmap\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use Modules\Heatmap\Models\Mongo\HeatmapUrl;

class HeatmapAPI extends RestController
{
    public static function getCode()
    {
	$user = \App::make('user');
	$domain = \Domains::get($_SERVER['HTTP_REFERER']);

	// generate user image
	\Screenshots::make($_SERVER['HTTP_REFERER'], $user);

        $parsed_url = parse_url($_SERVER['HTTP_REFERER']);

        if(isset($parsed_url['path']) === false)
        {
            $parsed_url['path'] = null;
        }

	$url = $parsed_url['host'].$parsed_url['path'];

        $heatmap_url = HeatmapUrl::where('url', '=', $url)->first();
        if(empty($heatmap_url))
        {
            $heatmap_url = HeatmapUrl::create([
                'user_id' => $user->id,
                'url' => $url,
                'user_count' => 0
            ]);
        }
        else
        {
            $heatmap_url->increment('user_count');
            $heatmap_url->save();
        }
        
        
        // CUSTOM JS back to the user
        return array(
            'function' => 'apply_function',
            'data' => array(
                'function' => "
                    var heat_data = new Array();
		    var body = document.body,
		    html = document.documentElement;
                    var count = 1;
                    
                    document.querySelector('body').onmousemove = function(ev)
                    {
                        console.log(ev.x + window.scrollX);
                        heat_data.push({
                            x: ev.x + window.scrollX,
                            y: ev.y + window.scrollY,
                            width:  Math.min(body.scrollWidth, body.offsetWidth, html.clientWidth, html.scrollWidth, html.offsetWidth),
			    height:  Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight)
                        });

                        if(heat_data.length >= 50)
                        {
                            data = {
                                key:'".\Request::input('key')."',
                                point_data: heat_data,
                                heatmap_url_id: '".$heatmap_url->id."',
                                value: count++
                            }
                                
                            swb('send', 'api/v1/heatmap/point', data);
                            
                            heat_data = new Array();
                        }
                    };
                "
            )
        );
    }
}