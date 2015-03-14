<?php

namespace Modules\Heatmap\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class HeatmapController extends Controller
{
    public function index()
    {
	return View::make('heatmap::index');
    }

    public function action_dashboard()
    {
	echo 'convert to laravel';die;
	$data = new \stdClass;

	$mongodb = \Mongo_Db::instance();

	// Get Unique domains
	$data->users = $mongodb->get_where('heatmap_users', array(
	    'user_id' => \Auth::get_user_id()[1],
	));

	$this->template->content = \View::Forge('private/dashboard', $data);
    }

    public function action_view($user_id)
    {
	echo 'convert to mongo'; die;
	// Get an instance
	$mongodb = \Mongo_Db::instance();
	$data = new \stdClass;

	$mongodb = \Mongo_Db::instance();

	$mongodb->where(array('_id' => new \MongoId($user_id)));

	$data->heatmap_user = $mongodb->get_one('heatmap_users');

	$data->heatmap = $mongodb->get_where('heatmap', array(
	    'user_id' => \Auth::get_user_id()[1],
	    'user' => $user_id
	));


	$mongodb->where(array('user_id' => $user_id));
	$data->url = $mongodb->get_one('screenshots');

	$this->template->content = \View::Forge('private/site/view', $data);
    }
}