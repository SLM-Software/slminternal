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

		$z = json_decode(file_get_contents($_ENV['IP_WHITELISTFILE']), TRUE);
		$this->container->logger->debug("\$_SERVER['REMOTE_ADDR']=" . $_SERVER['REMOTE_ADDR']);
		if (!in_array($_SERVER['REMOTE_ADDR'], $z['IPLIST']))
		{
			$this->container->logger->alert(__METHOD__ . ' Unauthorized attend to access service from IP: ' . $_SERVER['REMOTE_ADDR']);
			throw new Exception('Something went wrong - check the log!');
		}

		$myResponse = $next($request, $response);

		return $myResponse;
	}
}