<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Create logger
$logger = new BankID\Helpers\Logger();

// Create authentication class, add logger and get OAuth2 url
$auth = new BankID\Providers\ubki\Auth\OAuth2();
$auth->setLogger($logger);
$auth->setCredentials([
	"url" => "bankid.org.ua",
	"client_id" => "{client_id}",
	"client_secret" => "{client_secret}",
	"redirect_uri" => "https://google.com/",
]);
$oauth2_url = $auth->getOAuth2Url();

// Imagine that we've received code from BankID
$code = "apple";

// Set code to authentication class
$auth->setCode($code);

// Create provider class and load data
$bank_id = new BankID\Providers\ubki\UBKI($auth, "{processing_url}");

try {
	// Load data
	$data = $bank_id->getData();

	echo print_r($data, true);
} catch (Exception $ex) {
	echo print_r($ex->getMessage(), true).PHP_EOL;
	echo print_r($auth->getLogger()->getAll(), true);
}
