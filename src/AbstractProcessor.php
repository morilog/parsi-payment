<?php

namespace Laratalks\ParsiPayment;

abstract class AbstractProcessor {

	/**
	 * @var array
	 */
	protected $configs;

	/**
	 * @var
	 */
	protected $amount;

	public function __construct(array $configs)
	{
		$this->configs = $configs;
	}

	/**
	 * @param int $amount
	 * @return $this|bool
	 * @throws \Exception
	 */
	public function setAmount($amount)
	{
		if ( gettype( $amount ) != "integer" || $amount < 1000  )
		{
			throw new \Exception('$amount variable should be of type of integer and bigger than 1000');
		}

		$this->amount = $amount;
		return $this;
	}

	/**
	 * Magic method used for throwing errors
	 *
	 * @param string $method_name
	 * @param array $method_arguments
	 * @return void || Exception
	 */
	public function __call( $method_name , $method_arguments = [ ])
	{
		$method_name = "\\Laratalks\\ParsiPayment\\Exceptions\\" . ucfirst($method_name);

		if ( class_exists($method_name)) 
			return new $method_name(( ! empty($method_arguments[0]) ? $method_arguments[0] : '' ));

		throw new \Exception( "Method not found.");
	}

}
