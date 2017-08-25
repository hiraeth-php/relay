<?php

namespace Hiraeth\Relay;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Hiraeth;

/**
 *
 */
class SessionMiddleware
{
	/**
	 *
	 */
	public function __construct(Hiraeth\Application $app, Hiraeth\Configuration $config)
	{
		$this->app    = $app;
		$this->config = $config;
	}


	/**
	 *
	 */
	public function __invoke(Request $request, Response $response, callable $next)
	{
		$env_save_path = $this->app->getEnvironment('SESSION_SAVE_PATH', NULL);
		$cfg_save_path = $this->config->get('app', 'session.save_path', $env_save_path);

		if ($cfg_save_path) {
			session_save_path($this->app->getDirectory($cfg_save_path));
		}

		session_start();

		$response = $next($request, $response);

		return $response;
	}
}
