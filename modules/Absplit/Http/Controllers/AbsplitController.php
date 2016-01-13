<?php

namespace Modules\Absplit\Http\Controllers;

use \App\Http\Controllers\Controller;
use Modules\Absplit\Models\Absplit_Experiments;
use Modules\Absplit\Models\Absplit_Experiment_Data;

class AbsplitController extends Controller
{
    public function index()
    {
	return view('absplit::index');
    }

    public function getDashboard()
    {
	$experiments = Absplit_Experiments::take(10)
	    ->get();

	return view('absplit::dashboard', ['experiments' => $experiments]);
    }

    public function postDashboard()
    {
	// create a new site
	$experiment = Absplit_Experiments::create([
	    'url' => \Request::get('url'),
	    'url_pattern' => \Request::get('url'),
	    'active' => false,
	]);

	$experiment->user_id = \Auth::user()->id;
	$experiment->save();

	return redirect(action(
		'\Modules\Absplit\Http\Controllers\AbsplitController@getExperiment', [
		    'id' => $experiment->id
		]
	    )
	);
    }

    public function getExperiment($id)
    {
	$experiment = Absplit_Experiments::where('id', $id)->first();

	if(empty($experiment) === false)
	{
	    $url = $experiment->url;
	    $url_parsed = parse_url($url);

	    if(isset($url_parsed['path']) === false)
	    {
		$url_parsed['path'] = null;
	    }
	    $base_url = $url_parsed['scheme'].':'.'//'.$url_parsed['host'].$url_parsed['path'];

	    return view('absplit::experiment', [
		'container_class' => null,
		'experiment' => $experiment,
		'base_url' => $base_url
	    ]);
	}
	else
	{
	    return redirect()->back()->withErrors('Experiment Not Found, please contact support!');
	}
    }
    
    public function postExperiment()
    {
        $experiment = Absplit_Experiments::where('id', \Request::get('experiment_id'))->firstOrFail();
        
        $absplit_experiment_data = Absplit_Experiment_Data::create([
            'absplit__experiments_id' => $experiment->id,
            'js' => json_encode(\Request::get('changes')),
            'css' => null,
            'history' => json_encode(\Request::get('history'))
        ]);
        
        $absplit_experiment_data->save();
    }
}