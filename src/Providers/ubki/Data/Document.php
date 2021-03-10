<?php

namespace BankID\Providers\ubki\Data;

use BankID\Abstracts\Data\ADocument;
use BankID\Interfaces\Packet\IPacket;

class Document extends ADocument
{

	public function __construct(array $data, IPacket $packet)
	{
		$this->type = $data["type"] ?? null;
		$this->series = $data["series"] ?? null;
		$this->number = $data["number"] ?? null;
		$this->record_number = $data["recordNo"] ?? null;
		$this->issuer = $data["issue"] ?? null;
		$this->issue_date = $data["dateIssue"] ?? null;
		$this->expire_date = $data["dateExpiration"] ?? null;
		$this->issuer_country = $data["issueCountryIso2"] ?? null;

		$this->packet = $packet;
	}

}
