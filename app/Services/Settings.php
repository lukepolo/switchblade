<?php
namespace App\Services;

use App\Models\Settings as modelSettings;

class Settings
{
    static $settings;

    public function __construct()
    {
	static::$settings = \Cache::rememberForever('settings', function()
	{
	    echo 'NOT CACHED'; die;
	    foreach(modelSettings::get() as $setting)
	    {
		$settings[$setting->name] = $setting->data;
	    }
	    return $settings;
	});
    }
    public function get($setting)
    {
	if(isset(static::$settings[$setting]))
	{
	    return static::$settings[$setting];
	}
	else
	{
	    // THROW ERROR;
	    throw new \Exception('Missing "'.$setting.'" Setting');
	}
    }
}
