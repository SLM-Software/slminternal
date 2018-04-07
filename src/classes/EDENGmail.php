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
 * Date: 4/6/18
 * Time: 6:03 PM
 */

namespace API;
//require_once __DIR__ . '/../../vendor/google/apiclient/src/Google/Client.php';

use Slim;
//use Google_;

// ToDo - develop multiple .credentials file to various from emails. Right now, all emails are tied to syacko@spotlightmart.com
/**
 * Class EDENGmail
 */
class EDENGmail
{
	/**
	 * Class Variable area
	 */

	/**
	 * @var string $creditialsFileName Location of the gmail creditials for offline access to Google API's
	 */
	protected $creditialsFileName;

	/**
	 * @var string $csFileName Location of the gmail client ID
	 */
	protected $csFileName;

	/**
	 * @var string $accessTokenIssuer This holds the URL that issues the access token
	 */
	protected $accessTokenIssuer;

	/**
	 * @var "Slim\Http\RequestMonolog\Logger" $this->myLogger The instance of the Logger created at startup.
	 */
	protected $myLogger;

	/**
	 * Sends EDEN emails via Gmail
	 *
	 * This will send email via the Gmail API.
	 *
	 * @api
	 *
	 * @param Slim\Http\Request $request The request object implements the PSR 7
	 *                                        ServerRequestInterface with which you can inspect and
	 *                                        manipulate the HTTP request method, headers, and body.
	 *                                   subject (string)
	 *                                   fromName (string)
	 *                                   fromEmail (string)
	 *                                   toNames (array) of names to use for the emails in to.
	 *                                   toEmails (array) of emails to send this message
	 *                                   msgBody (array) of lines that make up the body of the email.
	 *
	 * @return array Keys: errCode, statusText, codeLoc, custMsg, retPack
	 *                      errCode is 0 for Success or 900 for error
	 *                      statusText contains system generated error message for debugging
	 *                      codeLoc is the class and method that throw the error
	 *                      custMsg is the message that is displayed to the end user (customer or member)
	 *                      retPack is the payload that is return to the caller
	 *
	 * @throws \Google_Exception
	 */
	public function processEmail(Slim\Http\Request $request)
	{
		$this->myLogger->debug(__METHOD__);
// Get the API client and construct the service object.
		$client = $this->getGmailClient();
		$service = new \Google_Service_Gmail($client);

//	SEND EMAIL
//		ToDo Valid the input fields are correct for email
//
		$strRawMessage = "From: " . $request->getQueryParam('fromName') . " <" . $request->getQueryParam('fromEmail') . ">\r\n";
//		for ($i = 0; $i < count($emailInfo['toEmails']); $i++)
//		{
//			if ($i == 0)
//			{
//				$strRawMessage .= "To: " . $request->getQueryParam('toNames') . " <" . $request->getQueryParam('toEmails') . ">";
//			} else
//			{
//				$strRawMessage .= ", " . $emailInfo['toNames'][$i] . " <" . $emailInfo['toEmails'][$i] . ">";
//			}
//		}
		$strRawMessage .= "\r\n";
		$strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($request->getQueryParam('subject')) . "?=\r\n";
		$strRawMessage .= "MIME-Version: 1.0\r\n";
		$strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
		$strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
//		foreach ($emailInfo['msgBody'] as $msgLine)
//		{
			$strRawMessage .= $request->getQueryParam($msgBody) . "\r\n\r\n<br/>";
//		}
		try
		{
			// The message needs to be encoded in Base64URL
			$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
			$msg = new \Google_Service_Gmail_Message();
			$msg->setRaw($mime);

			//The special value **me** can be used to indicate the authenticated user.
			$objSentMsg = $service->users_messages->send("me", $msg);
			$x = $objSentMsg;
		} catch (\Google_Exception $e)
		{
			print($e->getMessage());
		}
	}

	/**
	 * Returns a client request.
	 * @return  \GuzzleHttp\Client response object
	 */
	protected function buildClientRequest($client, string $method, string $apiServ, $endPoint, $headers, array $params = NULL)
	{
		$x = $apiServ;
		$y = $method;
		$z = $headers;
		$myParams = '';
		if ($params == NULL)
		{
//  @todo Support all method types. PUT, DELETE, POST, etc
			$res = $client->request($this->apiServices[$apiServ][$endPoint]['method'], '/' . $apiServ . '/' . $endPoint, ['verify' => FALSE, 'headers' => $headers]);
		} else
		{
//	@todo Support all method types. PUT, DELETE, POST, etc
			foreach ($params as $key => $value)
			{
				$myParams .= $key . '=' . $value . '&';
			}
			$res = $client->request($this->apiServices[$apiServ][$endPoint]['method'], '/' . $apiServ . '/' . $endPoint . '?' . $myParams, ['verify' => FALSE, 'headers' => $headers]);
		}
		return $res;
	}

	/**
	 * Returns a request header.
	 * @return  array
	 */
	protected function buildHeaders(string $apiEnv, string $apiServ)
	{
		return $headers = [
			'Authorization' => $this->execCURL($apiEnv, $apiServ),
			'Accept'        => 'application/json',
			'Cache-Control' => 'no-cache',
			//  @todo Read the verify value from the APIMonitor environment file for each environment listed in the API_ENVIRONMENTS setting
			'verify'        => FALSE,
		];

	}

	/**
	 * Returns a Auth0 access token
	 * @return  string
	 */
	protected function execCURL(string $apiEnv, string $apiServ)
	{
		try
		{
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL            => $this->accessTokenIssuer,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_ENCODING       => "",
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => "POST",
				CURLOPT_POSTFIELDS     => json_encode($this->apiPostFields[$apiEnv . '_' . $apiServ]),
				CURLOPT_HTTPHEADER     => array("content-type: application/json"),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
		} catch (\Exception $e)
		{
			$this->myLogger($e->getCode());
			$this->myLogger($e->getMessage());
		}

		return json_decode($response)->access_token;
	}

	/**
	 * Returns an authorized API client.
	 * @return Google_Client the authorized client object
	 * @throws \Google_Exception
	 */
	protected function getGmailClient()
	{
		$client = new \Google_Client();
		$client->addScope("https://mail.google.com/");
		$client->addScope("https://www.googleapis.com/auth/gmail.compose");
		$client->addScope("https://www.googleapis.com/auth/gmail.modify");
		$client->addScope("https://www.googleapis.com/auth/gmail.readonly");
		// Load previously authorized credentials from a file.
		if (file_exists($this->csFileName))
		{
			$client->setAuthConfig($this->csFileName);
		} else
		{
//  @todo THROW ERROR HERE - Client Id and Client Secret file with other needed settings for the Google_Client.
		}
		$client->setAccessType('offline');
		$client->setApprovalPrompt('force');

		// Load previously authorized credentials from a file.
		if (file_exists($this->creditialsFileName))
		{
			$accessToken = json_decode(file_get_contents($this->creditialsFileName), TRUE);
		} else
		{
//
//  @todo THROW ERROR HERE - ACCOUNT NEEDS TO BE REAUTHORIZED
//
//		    $authUrl = $client->createAuthUrl(); // THIS NEEDS TO BE OUTPUT SOME HOW
//	        SEE IF A AWS ALERT CAN BE CREATED TO ALERT USERS OF THE ISSUE.
//
//          Exchange authorization code for an access token.
//		    $accessToken = $client->authenticate('4/AABblIyBIy9VBVq60Ulfb2WHS8PodHtj6cN88i5Dzt3SCX7lRirmjrc');
//
//	        THE CODE FROM THE createAuthUrl needs to be put in a file and read by the line above.
//
//	        THE CODE BELOW MAY OR MAYNOT BE NEEDED
//          Store the credentials to disk.
//		    if(!file_exists(dirname($credentialsPath)))
//          {
//			    mkdir(dirname($credentialsPath), 0700, true);
//		    }
//		    file_put_contents($credentialsPath, json_encode($accessToken));
		}
		$client->setAccessToken($accessToken);

		// Refresh the token if it's expired.
		if ($client->isAccessTokenExpired())
		{
			file_put_contents($this->creditialsFileName, json_encode($client->getAccessToken($client->getRefreshToken())));
		}
		return $client;
	}

	/**
	 * EDENMemberMessage constructor
	 *
	 * @param $logger
	 * @param $db
	 */
	public function __construct($logger)
	{
		$this->myLogger = $logger;
		$this->myLogger->debug(__METHOD__);

		$envPath = '';
		if (array_key_exists('MAPP', getenv()))
		{
			$envPath = getenv('MAPP');
		} else if (array_key_exists('LAPP', getenv()))
		{
			$envPath = getenv('LAPP');
		} else
		{
			echo 'Missing MAPP or LAPP environment variable! System will not function without this being set.';
			throw new Exception('Missing MAPP or LAPP environment variable! System will not function without this being set.');
		};
		$dotEnv = new \Dotenv\Dotenv($envPath. '/utilities/.env/', 'Utilities.env');
		$dotEnv->load();
		$this->creditialsFileName = $envPath . '/utilities' . $_ENV['APP_CREDENTIALSFILE'];
		$this->csFileName = $envPath . '/utilities' . $_ENV['APP_CSFILE'];
		$this->accessTokenIssuer = $_ENV['API_ACCESSTOKEN_ISSUER'];
	}
}