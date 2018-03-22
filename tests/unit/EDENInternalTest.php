<?php


class EDENInternalTest extends \Codeception\Test\Unit
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
	 * @var \Logger
	 */
	protected $logger;

	/**
	 * @var \API Results
	 */
	protected $apiResults;

	protected function _before()
	{
		require __DIR__ . '/../../vendor/autoload.php';

// Instantiate the app
		$this->settings = require __DIR__ . '/../../src/settings.php';
		$app = new \Slim\App($this->settings);

// Set up dependencies
		require __DIR__ . '/../../src/dependencies.php';

// Register middleware
		require __DIR__ . '/../../src/middleware.php';

// Register routes
		require __DIR__ . '/../../src/routes.php';

// Start Logger
		$this->logger = new Monolog\Logger($this->settings['settings']['logger']['name']);
		$this->logger->pushProcessor(new Monolog\Processor\UidProcessor());
		$this->logger->pushHandler(new Monolog\Handler\StreamHandler($this->settings['settings']['logger']['path'], $this->settings['settings']['logger']['level']));
	}

	protected function _after()
	{
	}

	// tests
	public function testGetVersion()
	{
		$myEDENInternal = new \API\EDENInternal($this->logger, $this->settings['settings']['VERSION'], $this->settings['settings']['BUILD']);
		$this->apiResults = $myEDENInternal->getVersion();
		codecept_debug($this->apiResults);
		$this->assertTrue($this->apiResults['retPack']['version'] == $this->settings['settings']['VERSION']);
		$this->assertTrue($this->apiResults['retPack']['build'] == $this->settings['settings']['BUILD']);
		$this->logger->debug('test has been run');
	}

	public function testGetDeviceIdValidate_String()
	{
		$myEDENInternal = new \API\EDENInternal($this->logger);
		$this->apiResults = $myEDENInternal->getDeviceId();
		codecept_debug($this->apiResults);
		$myResult = $myEDENInternal->validateDeviceIdFormat_String($this->apiResults['retPack']);
		$this->assertTrue( $myResult['errCode'] == 0);
		$this->logger->debug('test has been run');

	}

	public function testNegativeGetDeviceIdValidate_String()
	{
		$myEDENInternal = new \API\EDENInternal($this->logger);
		$this->apiResults = $myEDENInternal->validateDeviceIdFormat_String('NzYxYTlkM2EtOWQ5My0xMWU3LTk2ZjQtOTg');
		codecept_debug($this->apiResults);
		$this->assertTrue( $this->apiResults['errCode'] == 900);
		$this->apiResults = $myEDENInternal->validateDeviceIdFormat_String($this->apiResults['retPack']);
		codecept_debug($this->apiResults);
		$this->assertTrue( $this->apiResults['errCode'] == 0);
		$this->logger->debug('test has been run');
	}

	public function testGetDeviceIdValidate_Request()
	{
		$myEDENInternal = new \API\EDENInternal($this->logger);
		$client = new \GuzzleHttp\Client(['base_uri' => 'https://' . $this->settings['settings']['curl']['host'] . ':' . $this->settings['settings']['curl']['port'], 'timeout' => 2.0]);
		$res = $client->request('GET', 'edeninternal/getdeviceid');
		$this->apiResults = json_decode($res->getBody());
		codecept_debug($this->apiResults);

		$client = new \GuzzleHttp\Client(['base_uri' => 'https://' . $this->settings['settings']['curl']['host'] . ':' . $this->settings['settings']['curl']['port'], 'timeout' => 2.0]);
		$res = $client->request('GET', 'EDENInternal/validatedeviceid?did=' . $this->apiResults->retPack . '&');
		$this->apiResults = json_decode($res->getBody());
		codecept_debug($this->apiResults);
		$this->assertTrue( $this->apiResults->errCode == 0);
	}

	public function testNegativeGetDeviceIdValidate_Request()
	{
		$myEDENInternal = new \API\EDENInternal($this->logger);
		$this->apiResults = $myEDENInternal->getDeviceId();
		$client = new \GuzzleHttp\Client(['base_uri' => 'https://' . $this->settings['settings']['curl']['host'] . ':' . $this->settings['settings']['curl']['port'], 'timeout' => 2.0]);
		$res = $client->request('GET', 'EDENInternal/validatedeviceid?did=' . substr($this->apiResults['retPack'], 0, 20) . '&');
		$this->apiResults = json_decode($res->getBody());
		codecept_debug($this->apiResults);
		$this->assertTrue( $this->apiResults->errCode == 900);

		$client = new \GuzzleHttp\Client(['base_uri' => 'https://' . $this->settings['settings']['curl']['host'] . ':' . $this->settings['settings']['curl']['port'], 'timeout' => 2.0]);
		$res = $client->request('GET', 'EDENInternal/validatedeviceid?did=' . $this->apiResults->retPack . '&');
		$this->apiResults = json_decode($res->getBody());
		codecept_debug($this->apiResults);
		$this->assertTrue( $this->apiResults->errCode == 0);
	}
}