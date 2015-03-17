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

	$url = trim($parsed_url['host'].$parsed_url['path'], '/');

        $heatmap_user = HeatmapUser::create([
	    'domain_id' => $domain->id,
            'url' => $url,
	]);

        // CUSTOM JS back to the user
        return array(
            'function' => 'apply_script',
            'data' => array(
                'url' => asset('assets/js/heatmap.min.js'),
                'callback' => "callback = function()
                {
                    var heat_data = new Array();

                    document.querySelector('body').onmousemove = function(ev)
                    {
                        heat_data.push({
                            x: ev.x + window.scrollX,
                            y: ev.y + window.scrollY,
                            width: window.innerWidth
                        });

                        if(heat_data.length >= 50)
                        {
                            $.ajax({
                                type: 'POST',
                                url: '".url('api/v1/heatmap/point')."',
                                data: {
                                    key:'".\Request::input('key')."',
                                    point_data: heat_data,
                                    user: '".$heatmap_user->id."'
                                }
                            });
                            heat_data = new Array();
                        }
                    };
                };"
            )
        );
    }
}