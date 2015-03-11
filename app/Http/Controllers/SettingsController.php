<?php

namespace App\Http\Controllers;

use App\Models\Settings;

class SettingsController extends Controller
{
    public function getIndex()
    {
	return view('settings', ['settings' => Settings::get()]);
    }

    public function postIndex()
    {
	foreach(\Request::except('_token') as $setting_id => $value)
	{
	    $setting = Settings::find($setting_id);

	    if($setting->data != $value)
	    {
		$setting->data = $value;
		$setting->save();
	    }
	}
	// Delete the cache
	\Cache::forget('settings');

	return redirect()->back()->with('success', 'You successfully updated the settings!');
    }
}