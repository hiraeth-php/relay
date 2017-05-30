<?php

namespace Hiraeth\Relay;

use Closure;
use Hiraeth;
use Relay;

/**
 *
 */
class Resolver implements Relay\ResolverInterface
{
	/**
	 *
	 */
	protected $broker = NULL;


	/**
	 *
	 */
	public function __construct(Hiraeth\Broker $broker)
	{
		$this->broker = $broker;
	}


	/**
	 *
	 */
	public function __invoke($middleware)
	{
		if ($middleware instanceof Closure) {
			return $middleware;
		} else {
			return $this->broker->make($middleware);
		}
	}
}
