<?php

namespace Modules\Heatmap\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use Modules\Heatmap\Models\Mongo\HeatmapUrl;
use Modules\Heatmap\Models\Mongo\HeatmapUser;

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
		'url' => $url
	    ]);
	}

	$heatmap_user = HeatmapUser::where('heatmap_url_id', '=', $heatmap_url->id)
	    ->where('ip', '=', \Request::getClientIp(true))
	    ->first();

	if(empty($heatmap_user))
	{
	    $heatmap_user = HeatmapUser::create([
		'heatmap_url_id' => $heatmap_url->id,
		'ip' => \Request::getClientIp(true)
	    ]);
	}

        // CUSTOM JS back to the user
        return array(
            'function' => 'apply_function',
            'data' => array(
                'function' => "
                    var swb_heat_data = new Array(),
		    swb_body = document.body,
		    swb_html = document.documentElement,
		    swb_value = 0;

                    document.querySelector('body').onmousemove = function(ev)
                    {
                        swb_heat_data.push({
                            x: ev.x + window.scrollX,
                            y: ev.y + window.scrollY,
                            width:  Math.min(swb_body.scrollWidth, swb_body.offsetWidth, swb_html.clientWidth, swb_html.scrollWidth, swb_html.offsetWidth),
			    height:  Math.max(swb_body.scrollHeight, swb_body.offsetHeight, swb_html.clientHeight, swb_html.scrollHeight, swb_html.offsetHeight),
			    swb_value: swb_value++
                        });

                        if(swb_heat_data.length >= 25)
                        {
                            var data = {
                                key:'".\Request::input('key')."',
				heatmap_url_id: '".$heatmap_url->id."',
				user_id: '".$heatmap_user->id."',
                                point_data: swb_heat_data,
				width: Math.min(swb_body.scrollWidth, swb_body.offsetWidth, swb_html.clientWidth, swb_html.scrollWidth, swb_html.offsetWidth)
                            }

                            swb('send', 'api/v1/heatmap/point', data);

                            swb_heat_data = new Array();
                        }
                    };

		    document.querySelector('body').onclick = function(ev)
                    {
			var data = {
			    key:'".\Request::input('key')."',
			    heatmap_url_id: '".$heatmap_url->id."',
			    user_id: '".$heatmap_user->id."',
			    click_data: {
				x: ev.x + window.scrollX,
				y: ev.y + window.scrollY,
				width:  Math.min(swb_body.scrollWidth, swb_body.offsetWidth, swb_html.clientWidth, swb_html.scrollWidth, swb_html.offsetWidth),
				height:  Math.max(swb_body.scrollHeight, swb_body.offsetHeight, swb_html.clientHeight, swb_html.scrollHeight, swb_html.offsetHeight),
			    },
			    width: Math.min(swb_body.scrollWidth, swb_body.offsetWidth, swb_html.clientWidth, swb_html.scrollWidth, swb_html.offsetWidth)
			}

			swb('send', 'api/v1/heatmap/click', data);
		    }
                "
            )
        );
    }
}