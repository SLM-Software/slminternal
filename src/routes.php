<?php
// Routes

$app->get('/slm/api/slminternal/getdeviceid', function ($request, $response, $args)
{
	$this->logger->info("/getdeviceid '/' route");
	$mySLMInternal = new \API\SLMInternal($this->logger);

	return $response->withJson($mySLMInternal->getDeviceId());
});

$app->get('/slm/api/slminternal/validatedeviceid', function ($request, $response, $args)
{
	$this->logger->info("/validatedeviceid '/' route");
	$mySLMInternal = new \API\SLMInternal($this->logger);

	return $response->withJson($mySLMInternal->validateDeviceIdFormat($request));
});

$app->get('/slm/api/slminternal/version', function ($request, $response, $args)
{
	$this->logger->info("version '/' route");
	$mySLMInternal = new \API\SLMInternal($this->logger);

	return $response->withJson($mySLMInternal->getVersion());
});

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("SLMInternal:catch-all '/' route");
	$data = array('firstName' => 'Scott', 'lastName' => 'Yacko');

	return $response->withJson($data);
});
