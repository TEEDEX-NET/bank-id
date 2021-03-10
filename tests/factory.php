<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Create logger
$logger = new BankID\Helpers\Logger();

// Create factory and load provider and auth classes
$factory = new BankID\Helpers\Factory(
	"ubki\UBKI",
	"{processing_url}",
	"OAuth2",
	[
		"url" => "bankid.org.ua",
		"client_id" => "{client_id}",
		"client_secret" => "{client_secret}",
		"redirect_uri" => "https://google.com/",
	],
	$logger
);
$auth = $factory->getAuthClass();
$bank_id = $factory->getProviderClass();

// Get OAuth2 url
$oauth2_url = $auth->getOAuth2Url();

// Imagine that we've received code from BankID
$code = "apple";

// Set code to authentication class
$auth->setCode($code);

try {
	// Load data
	$data = $bank_id->getData();

	echo print_r($data, true);
} catch (Exception $ex) {
	echo print_r($ex->getMessage(), true).PHP_EOL;
	echo print_r($auth->getLogger()->getAll(), true);
}
