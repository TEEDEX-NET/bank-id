<?php

namespace BankID\Providers\ubki\Data;

use BankID\Abstracts\Data\APersonal;
use BankID\Interfaces\Packet\IPacket;

class Personal extends APersonal
{

	public function __construct(array $data, IPacket $packet)
	{
		$this->first_name = $data["firstName"] ?? null;
		$this->middle_name = $data["middleName"] ?? null;
		$this->last_name = $data["lastName"] ?? null;
		$this->phone = $data["phone"] ?? null;
		$this->itn = $data["inn"] ?? null;
		$this->client_id = $data["clId"] ?? null;
		$this->description = $data["clIdText"] ?? null;
		$this->birthday = $data["birthDay"] ?? null;
		$this->sex = $data["sex"] ?? null;
		$this->email = $data["email"] ?? null;
		$this->resident = $data["resident"] ?? null;
		$this->updated = $data["dateModification"] ?? null;

		$this->packet = $packet;
	}

}
