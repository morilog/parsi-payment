<?php namespace Laratalks\ParsiPayment;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider {

	protected $defer = false;

	public function boot()
	{
		$this->package('laratalks/parsi-payment');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$configs = $this->app['config']->get('parsi-payment::config');
		$processor = $configs['default'];
		$processorConfigs = $configs[$processor];

		$class = 'Laratalks\ParsiPayment\PaymentProcessors\\'.$processor;

		if( class_exists($class) )
		{
			$this->app->bind(
				'Laratalks\ParsiPayment\PaymentProcessorInterface',
				new $class($processorConfigs)
			);
		}

	}

	public function provides()
	{
		return [];
	}
}
