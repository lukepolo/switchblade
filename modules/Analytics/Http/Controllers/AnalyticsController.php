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
	// IDS
	// Metrics
	$analyticsData = \LaravelAnalytics::getVisitorsAndPageViews(7);
	$active_users = \LaravelAnalytics::getActiveUsers();

	$analytics = null;
	foreach($analyticsData as $analyticData)
	{
	    $analytics['labels'][] = $analyticData['date']->toDateString();
	    $analytics['visitors'][] = $analyticData['visitors'];
	    $analytics['views'][] = $analyticData['pageViews'];
	}

	return view('analytics::dashboard', [
	    'pageviews' => json_encode($analytics),
	    'active' => $active_users
	]);
    }
}