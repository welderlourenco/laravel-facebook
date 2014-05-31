<?php namespace WelderLourenco\Facebook\Providers;

use Illuminate\Support\ServiceProvider;

use WelderLourenco\Facebook\Facebook;

class FacebookServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('welderlourenco/facebook');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['facebook'] = $this->app->share(function($app)
		{
			return new Facebook;
		});

		$this->app['config']->package('loginsocial/facebook', __DIR__ . '/../../../config');
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
