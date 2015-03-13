<?php namespace Modules\Screenshot\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class ScreenshotController extends Controller {

    public function index()
    {
	return View::make('screenshot::index');
    }

    public function getDashboard()
    {
	return View::make('screenshot::index');

	echo 'convert to laravel'; die;
        $mongodb = \Mongo_Db::instance();

        $data = new \stdClass;

        // Get Unique domains
        $data->domains = $mongodb->get_where('user_domains', array(
            'user_id' => \Auth::get_user_id()[1]
        ));

        if(count($data->domains) == 1)
        {
            // we can just redirect them to their view
            \Response::Redirect(\Uri::Create('screenshot/site/view/'.$data->domains[0]['_id']->{'$id'}));
        }
        else
        {
            $this->template->content = \View::Forge('private/dashboard', $data);
        }
    }
}