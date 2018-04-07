<?php
// Routes

// For Testing only
//var_dump($_SERVER);

$app->group('', function(){
	$this->get('/edeninternal/sendemail', function ($request, $response, $args)
	{
		$this->logger->info("/sendemail '/' route");
		$myEDENGmail = new \API\EDENGmail($this->logger, $this->get('settings'));

		return $response->withJson($myEDENGmail->processEmail($request));
	});
	$this->get('/edeninternal/getdeviceid', function ($request, $response, $args)
	{
		$this->logger->info("/getdeviceid '/' route");
		$myEDENInternal = new \API\EDENInternal($this->logger, '', '');

		return $response->withJson($myEDENInternal->getDeviceId());
	});
	$this->get('/edeninternal/validatedeviceid', function ($request, $response, $args)
	{
		$this->logger->info("/validatedeviceid '/' route");
		$myEDENInternal = new \API\EDENInternal($this->logger,'', '');

		return $response->withJson($myEDENInternal->validateDeviceIdFormat_Request($request));
	});
	$this->get('/edeninternal/version', function ($request, $response, $args)
	{
		$this->logger->info("version '/' route");
		$versionSetting = $this->get('settings')['VERSION'];
		$buildSetting = $this->get('settings')['BUILD'];
		$myEDENInternal = new \API\EDENInternal($this->logger, $versionSetting, $buildSetting);

		return $response->withJson($myEDENInternal->getVersion());
	});
	$this->get('/edeninternal/getmembermessage', function ($request, $response, $args)
	{
		$this->logger->info("getMemberMessage '/' route");
		$myEDENMemberMessage = new \API\EDENMemberMessage($this->logger, $this->db);

		return $response->withJson($myEDENMemberMessage->getMemberMessage_Request($request));
	});
})->add(new Middleware($container));