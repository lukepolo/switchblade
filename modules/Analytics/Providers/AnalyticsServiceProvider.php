<?php

namespace Modules\Analytics\Providers;

use Illuminate\Support\ServiceProvider;

class AnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
	$this->registerConfig();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
	$this->publishes([
	    __DIR__.'/../Config/config.php' => config_path('analytics.php'),
	]);
	$this->mergeConfigFrom(
	    __DIR__.'/../Config/config.php', 'analytics'
	);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
	return array();
    }
}
