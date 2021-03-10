<?php

namespace BankID\Interfaces\Packet;

interface IAddressPacket
{

	public function getAddresses(): array;

	public function getAddressFields(): array;

}
