<?php

namespace BankID\Helpers;

use BankID\Interfaces\Auth\IAuth;
use BankID\Interfaces\Provider\IProvider;

class Factory
{

	private string $provider_name;
	private IProvider $provider_class;
	private IAuth $auth_class;
	private string $processing_url;

	public function __construct(
		string $bank_id,
		string $processing_url,
		string $auth_type = "default",
		array $auth_params = [],
		?Logger $logger = null
	)
	{
		$class = "BankID\Providers\\$bank_id";

		if (!class_exists($class)) {
			throw new BankIDException("Provider class $class doesn't exist");
		} elseif (!in_array(IProvider::class, class_implements($class))) {
			throw new BankIDException(
				"Class $class doesn't implement IProvider interface"
			);
		}

		$this->provider_name = $class;
		$this->processing_url = $processing_url;

		$this->setAuthClass($auth_type, $auth_params, $logger);
		$this->setProviderClass();
	}

	private function setAuthClass(
		string $auth_type, array $auth_params, ?Logger $logger
	): void
	{
		$class = $this->provider_name;
		$auth_classes = call_user_func([$class, "getAuthClasses"]);

		if (!isset($auth_classes[$auth_type])) {
			throw new BankIDException(
				"Authentication type $auth_type " .
				"doesn't exist for provider $class"
			);
		} else if (
			!in_array(
				IAuth::class,
				class_implements($auth_classes[$auth_type])
			)
		) {
			throw new BankIDException(
				"Class of authentication type $auth_type" .
				"doesn't implement IAuth interface"
			);
		}

		$this->auth_class = new $auth_classes[$auth_type];
		$this->auth_class->setLogger($logger);
		$this->auth_class->setCredentials($auth_params);
	}

	private function setProviderClass(): void
	{
		$class = $this->provider_name;

		$this->provider_class = new $class(
			$this->auth_class, $this->processing_url
		);
	}

	public function getAuthClass(): IAuth
	{
		return $this->auth_class;
	}

	public function getProviderClass(): IProvider
	{
		return $this->provider_class;
	}

}
