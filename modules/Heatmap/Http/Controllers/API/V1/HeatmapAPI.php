<?php

namespace Modules\Heatmap\Http\Controllers\API\V1;

use \App\Http\Controllers\RestController;
use Modules\Heatmap\Models\Mongo\HeatmapUser;

class HeatmapAPI extends RestController
{
    public static function getCode()
    {
	$user = \App::make('user');
	$domain = \Domains::getDomain($user);

	// generate user image
	\Screenshots::make($_SERVER['HTTP_REFERER'], $user);

        $parsed_url = parse_url($_SERVER['HTTP_REFERER']);

        if(isset($parsed_url['path']) === false)
        {
            $parsed_url['path'] = null;
        }

	$url = $parsed_url['host'].$parsed_url['path'];

        $heatmap_user = HeatmapUser::create([
	    'domain_id' => $domain->id,
	    'user_id' => $user->id,
            'url' => $url
	]);

        // CUSTOM JS back to the user
        return array(
            'function' => 'apply_function',
            'data' => array(
                'function' => "
                    var heat_data = new Array();
		    var body = document.body,
		    html = document.documentElement;

		    var height = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
		    var width = Math.min(body.scrollWidth, body.offsetWidth, html.clientWidth, html.scrollWidth, html.offsetWidth);

                    document.querySelector('body').onmousemove = function(ev)
                    {
                        heat_data.push({
                            x: ev.x + window.scrollX,
                            y: ev.y + window.scrollY,
                            width: width,
			    height: height
                        });

                        if(heat_data.length >= 50)
                        {
                            data = {
                                key:'".\Request::input('key')."',
                                point_data: heat_data,
                                user: '".$heatmap_user->id."'
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