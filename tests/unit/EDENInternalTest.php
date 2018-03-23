<?php


class EDENInternalTest extends \Codeception\Test\Unit
{

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var \app
	 */
	protected $app;

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

		$app->run();
	}

	protected function _after()
	{
	}

	// tests
	public function testEDENInternal()
	{
		codecept_debug('Starting testEDENInternal - Executing getVersion:');
		$myEDENInternal = new \API\EDENInternal($this->logger, $this->settings['settings']['VERSION'], $this->settings['settings']['BUILD']);
		$this->apiResults = $myEDENInternal->getVersion();
		$assertResult['TestVersion'] = $this->assertTrue($this->apiResults['retPack']['version'] == $this->settings['settings']['VERSION']);
		$assertResult['TestBuild'] = $this->assertTrue($this->apiResults['retPack']['build'] == $this->settings['settings']['BUILD']);
		$this->displayAssertions($assertResult);
		$assertResult = NULL;

		codecept_debug('->> Executing validateDeviceIdFormat_String: Testing Invalid device id');
		$this->apiResults = $myEDENInternal->validateDeviceIdFormat_String('NzYxYTlkM2EtOWQ5My0xMWU3LTk2ZjQtOTg');
		$assertResult['TestInvalidDeviceId'] = $this->assertTrue($this->apiResults['errCode'] == 900);
		$this->displayAssertions($assertResult);
		$assertResult = NULL;

		codecept_debug('->> Executing validateDeviceIdFormat_String: Testing valid device id');
		$this->apiResults = $myEDENInternal->validateDeviceIdFormat_String($this->apiResults['retPack']);
		$assertResult['TestValidateDeviceId'] = $this->assertTrue($this->apiResults['errCode'] == 0);
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