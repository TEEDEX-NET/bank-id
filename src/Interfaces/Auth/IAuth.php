<?php

namespace BankID\Interfaces\Auth;

use BankID\Helpers\Logger;

interface IAuth
{

	public function setCredentials(array $credentials): void;

	public function getAuthorizationHeaders(): array;

	public function getMaskedAuthorizationHeaders(): array;

	public function getLogger(): ?Logger;

	public function setLogger(Logger $logger): void;

	public function getUrl(): string;

}
