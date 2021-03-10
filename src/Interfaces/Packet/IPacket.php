<?php

namespace BankID\Interfaces\Packet;

use BankID\Interfaces\Decryptor\IDecryptor;

interface IPacket {

	public function getDecryptor(): ?IDecryptor;

	public function isDecryptable(): bool;

}