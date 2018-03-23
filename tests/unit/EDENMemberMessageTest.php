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
	 * @var \Logger
	 */
	protected $logger;

	/**
	 * @var \DB
	 */
	protected $pdo;

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

	public function testEDENMemberMessage()
	{
		codecept_debug('Starting testEDENMember - Executing getMemberMessage_String:');
		$myEDENMemberMessage = new \API\EDENMemberMessage($this->logger, $this->pdo);
		$this->apiResults = $myEDENMemberMessage->getMemberMessage_String('test', 'EN-US');
		$assertResult['errCode'] = $this->assertTrue($this->apiResults['errCode'] == 0);
		$assertResult['statusText'] = $this->assertTrue($this->apiResults['statusText'] == 'Success');
		$assertResult['custMsg'] = $this->assertTrue($this->apiResults['custMsg']->membermessage == 'This is a test msg');
		$this->displayAssertions($assertResult);
		$assertResult = NULL;

		codecept_debug('->> Executing getMemberMessage_String (lower case localeid):');
		$this->apiResults = $myEDENMemberMessage->getMemberMessage_String('test', 'en-us');
		$assertResult['errCode'] = $this->assertTrue($this->apiResults['errCode'] == 0);
		$assertResult['statusText'] = $this->assertTrue($this->apiResults['statusText'] == 'Success');
		$assertResult['custMsg'] = $this->assertTrue($this->apiResults['custMsg']->membermessage == 'This is a test msg');
		$this->displayAssertions($assertResult);
		$assertResult = NULL;

		codecept_debug('->> Executing getMemberMessage_String (Invalid mmTag):');
		$this->apiResults = $myEDENMemberMessage->getMemberMessage_String('BADtest', 'en-us');
		$assertResult['errCode'] = $this->assertTrue($this->apiResults['errCode'] == 200);
		$assertResult['statusText'] = $this->assertTrue($this->apiResults['statusText'] == 'Not Found');
		$this->displayAssertions($assertResult);
		$assertResult = NULL;

		codecept_debug('->> Executing getMemberMessage_String (Invalid LocaleId):');
		$this->apiResults = $myEDENMemberMessage->getMemberMessage_String('BADtest', 'UK');
		$assertResult['errCode'] = $this->assertTrue( $this->apiResults['errCode'] == 200);
		$assertResult['statusText'] = $this->assertTrue($this->apiResults['statusText'] == 'Not Found');
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