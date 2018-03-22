<?php
// Application middleware
// e.g: $app->add(new \Slim\Csrf\Guard);

use Auth0\SDK\JWTVerifier;
use Auth0\SDK\Exception\CoreException;
use Auth0\SDK\Exception\InvalidTokenException;

class Middleware
{
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
		$this->container->logger->debug(__METHOD__);
	}

	/**
	 * Short Description
	 *
	 * Long Description
	 *
	 */
	public function __invoke($request, $response, $next)
	{
		$this->container->logger->debug(__METHOD__);

		$x = $_SERVER[REMOTE_HOST];
		$this->container->logger->debug("\$x=$x");

		$myResponse = $next($request, $response);

		return $myResponse;
	}
}