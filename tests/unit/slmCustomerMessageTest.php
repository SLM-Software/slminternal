<?php


class slmCustomerMessageTest extends \Codeception\Test\Unit
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
	 * @var \DB
	 */
	protected $db;

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

// Start Database
		$this->pdo = new PDO($this->settings['settings']['db']['dns'], $this->settings['settings']['db']['username'], $this->settings['settings']['db']['password']);
		$this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}

	protected function _after()
	{
	}

	public function testGetCustomerMessage_Request()
	{
		$client = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:8080/slm/api/', 'timeout' => 2.0]);
		$res = $client->request('GET', 'slminternal/getcustomermessage?cmtag=test&localeid=EN-US&');
		$this->apiResults = json_decode($res->getBody());
		codecept_debug($this->apiResults);
		$this->assertTrue( $this->apiResults->errCode == 0);
		$this->assertTrue( $this->apiResults->custMsg->customermessage == 'This is a test Message');
		$this->logger->debug('test has been run');
	}

	public function testNegativeGetCustomerMessage_Request()
	{
		$client = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:8080/slm/api/', 'timeout' => 2.0]);
		$res = $client->request('GET', 'slminternal/getcustomermessage?cmtag=BadTest&localeid=EN-US&');
		$this->apiResults = json_decode($res->getBody());
		codecept_debug($this->apiResults);
		$this->assertTrue( $this->apiResults->errCode == 200);
		$this->logger->debug('test has been run');
	}

	public function testGetCustomerMessage_String()
	{
		$mySLMCustomerMessage = new \API\EDENCustomerMessage($this->logger, $this->pdo);
		$this->apiResults = $mySLMCustomerMessage->getCustomerMessage_String('test', 'EN-US');
		codecept_debug($this->apiResults);
		$this->assertTrue( $this->apiResults['errCode'] == 0);
		$this->logger->debug('test has been run');
	}

	public function testNegativeGetCustomerMessage_String()
	{
		$mySLMCustomerMessage = new \API\EDENCustomerMessage($this->logger, $this->pdo);
		$this->apiResults = $mySLMCustomerMessage->getCustomerMessage_String('BADtest', 'EN-US');
		codecept_debug($this->apiResults);
		$this->assertTrue( $this->apiResults['errCode'] == 200);
		$this->logger->debug('test has been run');
	}
}