<?php


class EDENMemberMessageTest extends \Codeception\Test\Unit
{
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var \Settings
	 */
	protected $settings;

	/**
	 * @var \API Results
	 */
	protected $apiResults;

	protected function _before()
	{
	}

	protected function _after()
	{
	}

	public function testGetCustomerMessage_Request()
	{
		$dotEnv = new \Dotenv\Dotenv(__DIR__ . '/../../../../../../', 'eden.env');
		$dotEnv->load();

		codecept_debug('Starting testEDENInternalREST - Executing edeninternal/getmembermessage:');
		$this->client = new \GuzzleHttp\Client(['base_uri' => 'https://' . $_ENV['CURL_HOST'] . ':' . $_ENV['CURL_PORT'], 'timeout' => 2.0]);
		$res = $this->client->request('GET', 'edeninternal/getmembermessage?mmtag=test&localeid=EN-US&', ['verify' => false]);
		$this->apiResults = json_decode($res->getBody());
		$assertResult['errCode'] = $this->assertTrue( $this->apiResults->errCode == 0);
		$assertResult['statusText'] = $this->assertTrue( $this->apiResults->statusText == 'Success');
		$assertResult['custMsg'] = $this->assertTrue( $this->apiResults->custMsg->membermessage == 'This is a test msg');
		$this->displayAssertions($assertResult);
		$assertResult= NULL;

		codecept_debug('->> Executing edeninternal/getmembermessage (Not Found):');
		$res = $this->client->request('GET', 'edeninternal/getmembermessage?mmtag=BadTest&localeid=EN-US&', ['verify' => false]);
		$this->apiResults = json_decode($res->getBody());
		$assertResult['errCode'] = $this->assertTrue( $this->apiResults->errCode == 200);
		$assertResult['statusText'] = $this->assertTrue( $this->apiResults->statusText == 'Not Found');
		$this->displayAssertions($assertResult);
		$assertResult= NULL;

		codecept_debug('->> Executing edeninternal/getmembermessage (Not Found with lower case LocaleId):');
		$res = $this->client->request('GET', 'edeninternal/getmembermessage?mmtag=BadTest&localeid=en-us&', ['verify' => false]);
		$this->apiResults = json_decode($res->getBody());
		$assertResult['errCode'] = $this->assertTrue( $this->apiResults->errCode == 200);
		$assertResult['statusText'] = $this->assertTrue( $this->apiResults->statusText == 'Not Found');
		$this->displayAssertions($assertResult);
		$assertResult= NULL;

		codecept_debug('->> Executing edeninternal/getmembermessage (Mixed case mmTag with lower case LocaleID):');
		$res = $this->client->request('GET', 'edeninternal/getmembermessage?mmtag=Test&localeid=en-us&', ['verify' => false]);
		$this->apiResults = json_decode($res->getBody());
		$assertResult['errCode'] = $this->assertTrue( $this->apiResults->errCode == 0);
		$assertResult['statusText'] = $this->assertTrue( $this->apiResults->statusText == 'Success');
		$assertResult['custMsg'] = $this->assertTrue( $this->apiResults->custMsg->membermessage == 'This is a test msg');
		$this->displayAssertions($assertResult);
	}

	protected function displayAssertions($assertResult)
	{
		foreach ($assertResult as $key => $value)
		{
			if ($value == 0)
			{
				$resultDisplay = 'Passed';
			} else
			{
				$resultDisplay = 'Failed';
			}
			codecept_debug('-> Assertion[' . $key . '] ' . $resultDisplay);
		}
	}
}