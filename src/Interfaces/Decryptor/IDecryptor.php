<?php

namespace BankID\Interfaces\Decryptor;

interface IDecryptor {

	public function decrypt(?string $string): ?string;

}
