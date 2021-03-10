<?php

namespace BankID\Providers\ubki\Data;

use BankID\Abstracts\Data\AAddress;
use BankID\Interfaces\Packet\IPacket;

class Address extends AAddress
{

	public function __construct(array $data, IPacket $packet)
	{
		$this->type = $data["type"] ?? null;
		$this->country = $data["country"] ?? null;
		$this->state = $data["state"] ?? null;
		$this->area = $data["area"] ?? null;
		$this->city = $data["city"] ?? null;
		$this->district = $data["subTown"] ?? null;
		$this->street = $data["street"] ?? null;
		$this->home = $data["houseNo"] ?? null;
		$this->apartment = $data["flatNo"] ?? null;

		$this->packet = $packet;
	}

}
