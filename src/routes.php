<?php
// Routes

//$app->get('/getdeviceid', function ($request, $response, $args)
//{
//	$this->logger->info("/getdeviceid '/' route");
//	$mySLMInternal = new SLMInternal($this->logger);
//
//	return $response->withJson($mySLMInternal->getDeviceId());
//});

//$app->get('/validdeviceid', function ($request, $response, $args)
//{
//	$this->logger->info("/getdeviceid '/' route");
//
//	$mySLMINternal = new SLMInternal($this->logger);
//
//	return $response->withJson($mySLMInternal->validDeviceIdFormat($request));
//});

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
