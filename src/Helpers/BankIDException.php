<?php

namespace BankID\Helpers;

use Exception;
use Throwable;

class BankIDException extends Exception {

	public static function raise(
		string $message = "" , int $code = 0 , Throwable $previous = null
	) {
		throw new self($message, $code, $previous);
	}

}
