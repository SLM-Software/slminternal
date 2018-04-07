<?php
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
$dotEnv = new \Dotenv\Dotenv($envPath . '/.env/', 'eden.env');
$dotEnv->load();

return [
    'settings' => [
        'displayErrorDetails' => $_ENV['APP_DISPLAYERRORDETAILS'], // set to false in production
        'addContentLengthHeader' => $_ENV['APP_ADDCONTENTLENGTHHEADER'], // Allow the web server to send the content-length header
        'VERSION' => $_ENV['APP_VERSION'],
        'BUILD' => $_ENV['APP_BUILD'],
//        The following two settings are for gmail only - you will not see them in other services
        'APP_CREDENTIALSFILE' => $_ENV['APP_CREDENTIALSFILE'],
        'APP_CSFILE' => $_ENV['APP_CSFILE'],
//        The following setting is for edeninternal only - you will not see it in other services
        'IP_WHITELISTFILE' => $_ENV['IP_WHITELISTFILE'],

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'EDENINTERNAL',
            'path'  => __DIR__ . $_ENV['LOG_PATH'] . 'edeninternal.log',
            'level' => $_ENV['LOG_LEVEL'],
        ],

        // Database settings
        'db' => [
	        'dns' => $_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'] . ';port=' . $_ENV['DB_PORT'],
	        'username' => $_ENV['DB_USERNAME'],
	        'password' => $_ENV['DB_PASSWORD'],
        ],

        // Curl Settings
        'curl' => [
	        'host' => $_ENV['CURL_HOST'],
	        'port' => $_ENV['CURL_PORT'],
	        'certPath' => $_ENV['CURL_CERTPATH'],
	        'certFileName' => $_ENV['CURL_CERTFILENAME'],
        ],
    ],
];