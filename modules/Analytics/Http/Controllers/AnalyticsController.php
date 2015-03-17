<?php

namespace Modules\Analytics\Http\Controllers;

use \App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
	return view('analytics::index');
    }

    public function getDashboard()
    {
	$analyticsData = \LaravelAnalytics::getVisitorsAndPageViews(7);

	$analytics = null;
	foreach($analyticsData as $analyticData)
	{
	    $data['labels'][] = $analyticData['date']->toDateString();
	    $data['visitors'][] = $analyticData['visitors'];
	    $data['views'][] = $analyticData['pageViews'];
	}

	return view('analytics::dashboard', [
	    'data' => json_encode($data)
	]);
    }
}