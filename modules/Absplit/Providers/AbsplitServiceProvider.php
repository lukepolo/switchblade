<?php

namespace Modules\Absplit\Providers;

use Illuminate\Support\ServiceProvider;

class AbsplitServiceProvider extends ServiceProvider
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
	    __DIR__.'/../Config/config.php' => config_path('absplit.php'),
	]);
	$this->mergeConfigFrom(
	    __DIR__.'/../Config/config.php', 'absplit'
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
