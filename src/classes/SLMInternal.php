<?php
/**
 * This is the class file that is the parent of all SLM Internal based API's.
 *
 * API's for SLM Internal, services not available to the public, are contained here or in sub-class of SLM Internal
 * if the size and complexity of the API warrants it's own class.
 *
 */

namespace SLM;

/**
 * Class SLMInternal
 */
class SLMInternal
{
	/**
	 * @var "Slim\Http\RequestMonolog\Logger" $logger The instance of the Logger created at startup.
	 */
	protected $myLogger;

	/**
	 * generate DeviceId.
	 *
	 * @api
	 *
	 * @return array Keys: errCode, errText, errLoc, custMsg, retPack
	 */
	public function getDeviceId()
	{
		$this->myLogger->debug(__METHOD__);
		return array('errCode' => 0, 'errText' => 'Success', 'errLoc' => '', 'custMsg' => '', 'retPack' => $this->generateDeviceId());
	}

	/**
	 * Valid DeviceId has UUID 4 Time format.
	 *
	 * @api
	 *
	 * @param ServerRequestInterface $request The request object implements the PSR 7
	 *                                        ServerRequestInterface with which you can inspect and
	 *                                        manipulate the HTTP request method, headers, and body.
	 *
	 *
	 * @return array Keys: errCode, errText, errLoc, errCust, retPack
	 */
	public function validDeviceIdFormat($request)
	{
		$this->myLogger->debug(__METHOD__);
		$myDid = base64_decode($request->getQueryParam('did'));
		if (preg_match('/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-(8|9|a|b)[a-f0-9]{3}\-[a-f0-9]{12}/i', rtrim($myDid)) == TRUE)
		{
			$this->myLogger->debug(__METHOD__ . '/ valid UUID 4 Time format');
			$resultString = array('errCode' => 0, 'errText' => 'Success', 'errLoc' => '', 'custMsg' => '', 'retPack' => '');
		} else
		{
			$this->myLogger->warning(__METHOD__ . '/ Invalid UUID 4 Time format' . trim($myDid));
			$resultString = array('errCode' => 900, 'errText' => 'Invalid device id', 'errLoc' => __METHOD__, 'custMsg' => '', 'retPack' => $this->generateDeviceId());
		}

		return $resultString;
	}

	/**
	 * @return base64 string
	 */
	protected function generateDeviceId()
	{
		$this->myLogger->debug(__METHOD__);
		return base64_encode(shell_exec('uuidgen -t'));
	}

	/**
	 * SLMInternal constructor.
	 *
	 * @param $logger
	 */
	public function __construct($logger)
	{
		$this->myLogger = $logger;
		$this->myLogger->debug(__METHOD__);
	}
}