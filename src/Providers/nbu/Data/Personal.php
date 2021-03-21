<?php

namespace BankID\Providers\nbu\Data;

use BankID\Abstracts\Data\APersonal;
use BankID\Interfaces\Packet\IPacket;

class Personal extends APersonal
{

	public function __construct(array $data, IPacket $packet)
	{
		$this->last_name = $data["lastName"] ?? null;
		$this->first_name = $data["firstName"] ?? null;
		$this->middle_name = $data["middleName"] ?? null;
		$this->phone = $data["phone"] ?? null;
		$this->itn = $data["inn"] ?? null;
		$this->client_id = $data["clId"] ?? null;
		$this->description = $data["clIdText"] ?? null;
		$this->birthday = $data["birthDay"] ?? null;
		$this->sex = $data["sex"] ?? null;
		$this->email = $data["email"] ?? null;
		$this->social_status = $data["socStatus"] ?? null;
		$this->is_pep = $data["lagPEPs"] ?? null;
		$this->is_terrorist = $data["flagPersonTerror"] ?? null;
		$this->is_restriction = $data["flagRestriction"] ?? null;
		$this->is_top_level_risk = $data["flagTopLevelRisk"] ?? null;
		$this->resident = $data["uaResident"] ?? null;

		$this->packet = $packet;
	}

}
