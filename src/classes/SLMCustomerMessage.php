<?php
/**
 * Short Description
 *
 * Long Description
 *
 */
/**
 * Created by PhpStorm.
 * User: syacko
 * Date: 9/6/17
 * Time: 11:45 AM
 */

namespace API;

/**
 * Class SLMCustomerMessage
 */
class SLMCustomerMessage
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
	 * Short Description
	 *
	 * Long Description
	 *
	 * @api
	 *
	 * @param ServerRequestInterface $request The request object implements the PSR 7
	 *                                        ServerRequestInterface with which you can inspect and
	 *                                        manipulate the HTTP request method, headers, and body.
	 *
	 *          The query elements in the URI are as follow:
	 *          Required elements:
	 *              pemail    = primary email [varchar(100)]
	 *              pphone    = primary phone [bigint]
	 *              fname     = first name [varchar(25)]
	 *              lname     = last name [varchar(30)]
	 *              pword     = password (Must be hashed by caller) [varchar(100)]
	 *
	 *          Option elements:
	 *              ppmethod  = primary payment method [json]
	 *              sgender   = supplied gender [char(6)]
	 *              bdate     = birthdate [json]
	 *
	 * @todo What still needs to be done before going to production
	 *
	 * @return array  Keys: errCode, errText, errLoc, custMsg, retPack
	 *                      errCode is 0 for Success or 900 for error
	 *                      errText contains system generated error message for debugging
	 *                      errLoc is the class and method that throw the error
	 *                      custMsg is the message that is displayed to the end user (customer or member)
	 *                      retPack is the payload that is return to the caller
	 */
	public function myMethodNameHere($request)
	{
		$this->myLogger->debug(__METHOD__);

		// Get Query Param
		$request->getQueryParam('did');

		/**
		 * Code goes here
		 */

		return $resultString;
	}

	/**
	 * Class Constructor with a parent.
	 *
	 * @param $logger
	 */
	public function __construct($logger)
	{
		parent::__construct($logger);
		$this->myLogger->debug(__METHOD__);
	}

	/**
	 * Class Constructor without a parent
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