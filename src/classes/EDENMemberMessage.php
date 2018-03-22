<?php
/**
 * Manages message displayed to the customer
 *
 * This is a collection of methods that are used to store, retrieve and update message displayed to the customer in various langauges.
 *
 */
/**
 * Created by PhpStorm.
 * User: syacko
 * Date: 9/6/17
 * Time: 11:45 AM
 */

namespace API;

use Slim;
/**
 * Class SLMCustomerMessage
 */
class EDENMemberMessage extends EDENInternal
{
	/**
	 * Class Variable area
	 */
	/**
	 * @var "Slim\Http\RequestMonolog\Logger" $logger The instance of the Logger created at startup.
	 */
	protected $myLogger;

	/**
	 * @var PDO $db The instance of the Postgresql PDO connect created at startup.
	 */
	protected $myDB;

	/**
	 * @var {type} {Description}
	 */
	protected $variable;

	/**
	 * This will get a customer message from the database
	 *
	 * Based on the parameters supplied in the call, this method will return a message in the langauge requested.
	 *
	 * @api
	 *
	 * @param Slim/Http/Request $request
	 *
	 *          The query elements in the URI are as follow:
	 *          Required elements:
	 *              mmtag   = member message tag [varchar(50)]
	 *              localeid = local idenitifer [varchar(10)]
	 *
	 *          Option elements:
	 *              none at this time.
	 *
	 * @todo What still needs to be done before going to production
	 *
	 * @return array  Keys: errCode, statusText, codeLoc, custMsg, retPack
	 *                      errCode is 0 for Success or 900 for error
	 *                      statusText contains system generated error message for debugging
	 *                      codeLoc is the class and method that throw the error
	 *                      custMsg is the message that is displayed to the end user (customer or member)
	 *                      retPack is the payload that is return to the caller
	 */
	public function getMemberMessage_Request(Slim\Http\Request $request)
	{
		$this->myLogger->debug(__METHOD__);

		$myMMTag = strtolower($request->getQueryParam('mmtag'));
		$myLocaleId = strtoupper($request->getQueryParam('localeid'));
		return $this->getMemberMessage($myMMTag, $myLocaleId);
	}

	/**
	 * This will get a customer message from the database
	 *
	 * Based on the parameters supplied in the call, this method will return a message in the langauge requested.
	 *
	 * @api
	 *
	 * @param String $mmTag - This is the member message tag
	 * @param String $localeId - This is the locale language id used.
	 *
	 *          The query elements in the URI are as follow:
	 *          Required elements:
	 *              mmtag   = member message tag [varchar(50)]
	 *              localeid = local idenitifer [varchar(10)]
	 *
	 *          Option elements:
	 *              none at this time.
	 *
	 * @todo What still needs to be done before going to production
	 *
	 * @return array  Keys: errCode, statusText, codeLoc, custMsg, retPack
	 *                      errCode is 0 for Success or 900 for error
	 *                      statusText contains system generated error message for debugging
	 *                      codeLoc is the class and method that throw the error
	 *                      custMsg is the message that is displayed to the end user (customer or member)
	 *                      retPack is the payload that is return to the caller
	 */
	public function getMemberMessage_String(string $mmTag, string $localeId)
	{
		$this->myLogger->debug(__METHOD__);

		return $this->getCustomerMessage($mmTag, $localeId);
	}

	/**
	 * @param string $myMMTag
	 * @param string $myLocaleId
	 *
	 * @return array
	 */
	protected function getMemberMessage(string $myMMTag, string $myLocaleId)
	{
		if (!isset($myMMTag) && !isset($myLocaleId) )
		{
			return array('errCode' => 201, 'statusText' => 'Empty mmtag and/or localeid', 'codeLoc' => __METHOD__, 'custMsg' => '', 'retPack' => '');
		}

		$qCustMessage = $this->myDB->prepare('select membermessage from eden.membermessages where lovkey = :mmtag and localeidentifier = :localeid');
		$qCustMessage->bindParam(':mmtag', $myMMTag, $this->myDB::PARAM_STR);
		$qCustMessage->bindParam(':localeid', $myLocaleId, $this->myDB::PARAM_STR);
		$myException = '';
		try
		{
			$qCustMessage->execute();
			$this->myLogger->debug('Row Count:' . $qCustMessage->rowCount());
			$custMessage = $qCustMessage->fetch();
			if ($qCustMessage->rowCount() == 0 )
			{
				$this->myLogger->debug('Row Count is ZERO');
				$resultString = array('errCode' => 200, 'statusText' => 'Not Found', 'codeLoc' => '', 'custMsg' => $custMessage, 'retPack' => '');
			}
			else
			{
				$this->myLogger->debug('Row Count is greater than zero');
				$resultString = array('errCode' => 0, 'statusText' => 'Success', 'codeLoc' => '', 'custMsg' => $custMessage, 'retPack' => '');
			}
		} catch (PDOException $e)
		{
			$myException = $e;
			$resultString = array('errCode' => 900, 'statusText' => 'PDOException', 'codeLoc' => __METHOD__, 'custMsg' => 'error', 'retPack' => $e);
			$this->logger->info(serialize($e));
		}
		$this->myLogger->info(serialize($custMessage));

		return $resultString;
	}

	/**
	 * SLMCustomerMessage constructor
	 *
	 * @param $logger
	 * @param $db
	 */
	public function __construct($logger, $db)
	{
		$this->myLogger = $logger;
		$this->myLogger->debug(__METHOD__);

		$this->myDB = $db;
	}
}