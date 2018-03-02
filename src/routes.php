<?php
// Routes

// For Testing only
//var_dump($_SERVER);

$app->get('/edeninternal/getdeviceid', function ($request, $response, $args)
{
	$this->logger->info("/getdeviceid '/' route");
	$myEDENInternal = new \API\EDENInternal($this->logger);

	return $response->withJson($myEDENInternal->getDeviceId());
});

$app->get('/edeninternal/validatedeviceid', function ($request, $response, $args)
{
	$this->logger->info("/validatedeviceid '/' route");
	$myEDENInternal = new \API\EDENInternal($this->logger);

	return $response->withJson($myEDENInternal->validateDeviceIdFormat_Request($request));
});

$app->get('/edeninternal/version', function ($request, $response, $args)
{
	$this->logger->info("version '/' route");
	$curlSettings = $this->get('settings')['curl'];
	$myEDENInternal = new \API\EDENInternal($this->logger, $curlSettings);

	return $response->withJson($myEDENInternal->getVersion());
});

$app->get('/edeninternal/getcustomermessage', function ($request, $response, $args)
{
	$this->logger->info("getCustomerMessage '/' route");
	$myEDENCustomerMessage = new \API\EDENCustomerMessage($this->logger, $this->db);

	return $response->withJson($myEDENCustomerMessage->getCustomerMessage_Request($request));
});

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("EDENInternal:catch-all '/' route");
	$data = array('firstName' => 'Scott', 'lastName' => 'Yacko');

	return $response->withJson($data);
});
