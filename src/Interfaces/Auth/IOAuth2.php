<?php

namespace BankID\Interfaces\Auth;

interface IOAuth2 extends IAuth
{

	public function getOAuth2Url(): string;

	public function setCode(string $code): void;

}
