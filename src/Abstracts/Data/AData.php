<?php

namespace BankID\Abstracts\Data;

use BankID\Helpers\BankIDException;
use BankID\Interfaces\Packet\IPacket;

abstract class AData {

	protected IPacket $packet;

	protected function get(?string $attribute): ?string {
		$decryptor = $this->packet->getDecryptor();

		if (!$this->packet->isDecryptable()) {
			return $this->$attribute;
		}

		if (!$decryptor) {
			BankIDException::raise(
				"Cant find decryptor for decryptable object"
			);
		}


		return $decryptor->decrypt($this->$attribute);

	}

}
