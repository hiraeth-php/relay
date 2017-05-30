<?php

namespace Hiraeth\Relay;

use Hiraeth;
use Relay;

/**
 *
 */
class RunnerDelegate implements Hiraeth\Delegate
{
	/**
	 * The Hiraeth configuration instance
	 *
	 * @access protected
	 * @var Hiraeth\Configuration
	 */
	protected $config = NULL;


	/**
	 * A resolver responsible for constructing middleware instances
	 *
	 * @access protected
	 * @var Resolver The resolver instance
	 */
	protected $resolver = NULL;


	/**
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass()
	{
		return 'Relay\Runner';
	}


	/**
	 * Get the interfaces for which the delegate provides a class.
	 *
	 * @static
	 * @access public
	 * @return array A list of interfaces for which the delegate provides a class
	 */
	static public function getInterfaces()
	{
		return [];
	}


	/**
	 * Construct the relay delegate
	 *
	 * @access public
	 * @param Hiraeth\Configuration $config The Hiraeth configuration instance
	 * @param Relay\ResolverInterface $resolver A resolver responsible for constructing middleware instances
	 * @return void
	 */
	public function __construct(Hiraeth\Configuration $config, Relay\ResolverInterface $resolver)
	{
		$this->config   = $config;
		$this->resolver = $resolver;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Broker $broker The dependency injector instance
	 * @return Relay\Runner The instance of our relay runner
	 */
	public function __invoke(Hiraeth\Broker $broker)
	{
		$queue  = $this->config->get('relay', 'middleware.queue', array());
		$runner = new Relay\Runner($queue, $this->resolver);

		if (in_array('Relay\Middleware\SessionHeadersHandler', $queue)) {
			ini_set('session.use_cookies', FALSE);
			ini_set('session.use_only_cookies', TRUE);
			ini_set('session.use_trans_sid', FALSE);
			ini_set('session.cache_limiter', '');
		}

		$broker->share($runner);

		return $runner;
	}
}
