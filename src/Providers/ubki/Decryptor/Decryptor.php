<?php

namespace BankID\Providers\ubki\Decryptor;

use BankID\Helpers\BankIDException;
use BankID\Interfaces\Decryptor\IDecryptor;

class Decryptor implements IDecryptor {

	private string $cert_file;
	private $private_key;

	public function __construct() {
		$this->cert_file = realpath(__DIR__).DIRECTORY_SEPARATOR."..".
			DIRECTORY_SEPARATOR."cert".DIRECTORY_SEPARATOR."rsa_key.pem";

		if (
			!(
				$this->cert_file &&
				file_exists($this->cert_file) &&
				$this->private_key = openssl_get_privatekey(
					file_get_contents($this->cert_file)
				)
			)
		) {
			BankIDException::raise("Incorrect path to private key");
		}
	}

	public function decrypt(?string $string): ?string {

		if (!$string) {
			return $string;
		}

		$result_string = "";
		$base64_decoded_string = base64_decode($string);

		if (
			openssl_private_decrypt(
				$base64_decoded_string,
				$result_string,
				$this->private_key
			)
		) {
			return $result_string;
		} else {
			BankIDException::raise("Can't decrypt data");

			return "";
		}
	}

}
