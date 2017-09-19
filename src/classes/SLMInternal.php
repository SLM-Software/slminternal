<?php
/**
 * This is the class file that is the parent of all SLM Internal based API's.
 *
 * API's for SLM Internal, services not available to the public, are contained here or in sub-class of SLM Internal
 * if the size and complexity of the API warrants it's own class.
 *
 */

namespace API;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Slim;

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
	 * Valid DeviceId has UUID 4 Time format.
	 *
	 * @api
	 *
	 * @param Slim\Http\Request $request The request object implements the PSR 7
	 *                                        ServerRequestInterface with which you can inspect and
	 *                                        manipulate the HTTP request method, headers, and body.
	 *
	 *
	 * @return array Keys: errCode, statusText, codeLoc, custMsg, retPack
	 *                      errCode is 0 for Success or 900 for error
	 *                      statusText contains system generated error message for debugging
	 *                      codeLoc is the class and method that throw the error
	 *                      custMsg is the message that is displayed to the end user (customer or member)
	 *                      retPack is the payload that is return to the caller

	 */
	public function validateDeviceIdFormat_Request(Slim\Http\Request $request)
	{
		$this->myLogger->debug(__METHOD__);
		$myDid = base64_decode($request->getQueryParam('did'));

		return $this->validateDeviceId($myDid);
	}

	/**
	 * Valid DeviceId has UUID 4 Time format.
	 *
	 * @api
	 *
	 * @param String $did
	 *
	 * @return array Keys: errCode, statusText, codeLoc, custMsg, retPack
	 *                      errCode is 0 for Success or 900 for error
	 *                      statusText contains system generated error message for debugging
	 *                      codeLoc is the class and method that throw the error
	 *                      custMsg is the message that is displayed to the end user (customer or member)
	 *                      retPack is the payload that is return to the caller

	 */
	public function validateDeviceIdFormat_String(String $did)
	{
		$this->myLogger->debug(__METHOD__);
		$myDid = base64_decode($did);

		return $this->validateDeviceId($myDid);
	}

	/**
	 * generate DeviceId.
	 *
	 * @api
	 *
	 * @return array Keys: errCode, statusText, codeLoc, custMsg, retPack
	 */
	public function getDeviceId()
	{
		$this->myLogger->debug(__METHOD__);
		return array('errCode' => 0, 'statusText' => 'Success', 'codeLoc' => __METHOD__, 'custMsg' => '', 'retPack' => $this->generateDeviceId());
	}

	/**
	 * return the version of the API being called.
	 *
	 * @api
	 *
	 * @return array Keys: errCode, statusText, codeLoc, custMsg, retPack
	 */
	public function getVersion()
	{
		$client = new \GuzzleHttp\Client(['base_uri' => 'http://localhost:8080/slm/api/', 'timeout' => 2.0]);
		$res = $client->request('GET', 'slminfo/version');
		$retValue = substr($res->getBody(), 0);
		$myObj = json_decode($retValue);
		$resultString = array('errCode' => 0, 'statusText' => 'Success', 'codeLoc' => __METHOD__, 'custMsg' => '', 'retPack' => (array) $myObj->retPack);
		return $resultString;
	}

	/**
	 * @return base64 string
	 */
	protected function generateDeviceId()
	{
		$this->myLogger->debug(__METHOD__);
		return base64_encode(Uuid::uuid1());
	}

	/**
	 * @return array Keys: errCode, statusText, codeLoc, custMsg, retPack
	 */
	protected function validateDeviceId(String $myDid)
	{
		$this->myLogger->debug(__METHOD__);
		if (preg_match('/[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-(8|9|a|b)[a-f0-9]{3}\-[a-f0-9]{12}/i', rtrim($myDid)) == TRUE)
		{
			$this->myLogger->debug(__METHOD__ . '/ valid UUID 4 Time format');
			$resultString = array('errCode' => 0, 'statusText' => 'Success', 'codeLoc' => __METHOD__, 'custMsg' => '', 'retPack' => '');
		} else
		{
			$this->myLogger->warning(__METHOD__ . '/ Invalid UUID 4 Time format' . trim($myDid));
			$resultString = array('errCode' => 900, 'statusText' => 'Invalid device id', 'codeLoc' => __METHOD__, 'custMsg' => '', 'retPack' => $this->generateDeviceId());
		}
		return $resultString;
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