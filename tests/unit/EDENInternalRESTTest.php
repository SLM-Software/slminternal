<?php


class EDENInternalRESTTest extends \Codeception\Test\Unit
{

	/**
	 * @var \UnitTester
	 */
	protected $tester;

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

	// tests
	public function testEDENInternalRESTGetVersion()
	{
		$dotEnv = new \Dotenv\Dotenv(__DIR__ . '/../../../../../../', 'eden.env');
		$dotEnv->load();

		codecept_debug('Starting testEDENInternalREST - Executing edeninternal/version:');
		$this->client = new \GuzzleHttp\Client(['base_uri' => 'https://' . $_ENV['CURL_HOST'] . ':' . $_ENV['CURL_PORT'], 'timeout' => 2.0]);
		$res = $this->client->request('GET', 'edeninternal/version', ['verify' => false]);
		$this->apiResults = json_decode($res->getBody());
		$assertResult['TestVersion'] = $this->assertTrue( $this->apiResults->retPack->version == $_ENV['APP_VERSION']);
		$assertResult['TestBuild'] = $this->assertTrue($this->apiResults->retPack->build == $_ENV['APP_BUILD']);
		$this->displayAssertions($assertResult);
		$assertResult = NULL;

		codecept_debug('Starting testEDENInternalREST - Executing edeninternal/getdeviceid:');
		$res = $this->client->request('GET', 'edeninternal/getdeviceid', ['verify' => false]);
		$this->apiResults = json_decode($res->getBody());
		codecept_debug('-> Executing edeninternal/validatedeviceid:');
		$this->apiResults = json_decode($res->getBody());
		$assertResult['DeviceIDValidated'] = $this->assertTrue( $this->apiResults->errCode == 0);
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