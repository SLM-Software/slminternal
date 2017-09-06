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

//$app->get('/version', function ($request, $response, $args)
//{
//	$this->logger->info("version '/' route");
//
//	$mySLMCurl = new SLMCurl($this->logger);
//	$mySLMCurl->callAPI('SLMInfo', 'version');
//
////	$body = $response->getBody();
////	$body->write($curl->response);
//
////	return $response->withBody($body);
//});

$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("SLMInternal:catch-all '/' route");

    $data = array('firstName' => 'Scott', 'lastName' => 'Yacko');
    // Render index view
	return $response->withJson($data);
});
