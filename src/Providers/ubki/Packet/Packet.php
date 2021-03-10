<?php

namespace BankID\Providers\ubki\Packet;

use BankID\Abstracts\Data\APersonal;
use BankID\Abstracts\Packet\APacket;
use BankID\Interfaces\Packet\IAddressPacket;
use BankID\Interfaces\Packet\IDocumentPacket;
use BankID\Interfaces\Packet\IPersonalPacket;
use BankID\Interfaces\Packet\IScanPacket;
use BankID\Providers\ubki\Data\Address;
use BankID\Providers\ubki\Data\Document;
use BankID\Providers\ubki\Data\Personal;
use BankID\Providers\ubki\Data\Scan;
use BankID\Providers\ubki\Decryptor\Decryptor;

class Packet
	extends APacket
	implements IAddressPacket, IDocumentPacket, IPersonalPacket, IScanPacket
{

	public function getPersonalData(): APersonal
	{
		return new Personal($this->data["customer"], $this);
	}

	public function getDocuments(): array
	{
		$data = [];

		foreach ($this->data["customer"]["documents"] as $document) {
			$obj = new Document($document, $this);
			$data[$obj->getType()] = $obj;
		}

		return $data;
	}

	public function getAddresses(): array
	{
		$data = [];

		foreach ($this->data["customer"]["addresses"] as $address) {
			$obj = new Address($address, $this);
			$data[$obj->getType()] = $obj;
		}

		return $data;
	}

	public function getScans(): array
	{
		$data = [];

		foreach ($this->data["customer"]["scans"] as $scan) {
			$obj = new Scan($scan, $this);
			$data[$obj->getType()] = $obj;
		}

		return $data;
	}

	public function getAddressFields(): array
	{
		return [
			"addresses" => [
				[
					"type" => "factual",
					"fields" => [
						"country",
						"state",
						"area",
						"city",
						"street",
						"houseNo",
						"flatNo",
						"dateModification",
					],
				],
				[
					"type" => "juridical",
					"fields" => [
						"country",
						"state",
						"area",
						"city",
						"street",
						"houseNo",
						"flatNo",
						"dateModification",
					],
				],
				[
					"type" => "birth",
					"fields" => [
						"country",
						"state",
						"area",
						"city",
						"street",
						"houseNo",
						"flatNo",
						"dateModification",
					],
				],
			],
		];
	}

	public function getDocumentFields(): array
	{
		return [
			"documents" => [
				[
					"type" => "passport",
					"fields" => [
						"series",
						"number",
						"issue",
						"dateIssue",
						"dateExpiration",
						"issueCountryIso2",
						"dateModification",
					],
				],
				[
					"type" => "zpassport",
					"fields" => [
						"series",
						"number",
						"issue",
						"dateIssue",
						"dateExpiration",
						"issueCountryIso2",
						"dateModification",
					],
				],
				[
					"type" => "ident",
					"fields" => [
						"series",
						"number",
						"issue",
						"dateIssue",
						"dateExpiration",
						"issueCountryIso2",
						"dateModification",
					],
				],
				[
					"type" => "idpassport",
					"fields" => [
						"number",
						"recordNo",
						"issue",
						"dateIssue",
						"dateExpiration",
						"issueCountryIso2",
						"dateModification",
					],
				],
				[
					"type" => "govregistration",
					"fields" => [
						"series",
						"number",
						"issue",
						"dateIssue",
						"dateExpiration",
						"issueCountryIso2",
						"dateModification",
					],
				],
			],
		];
	}

	public function getPersonalFields(): array
	{
		return [
			"fields" => [
				"firstName",
				"middleName",
				"lastName",
				"phone",
				"inn",
				"clId",
				"clIdText",
				"birthDay",
				"email",
				"sex",
				"resident",
				"dateModification",
			],
		];
	}

	public function getScanFields(): array
	{
		return [
			"scans" => [
				[
					"type" => "passport",
					"fields" => [
						"link",
						"dateCreate",
						"extension",
						"dateModification",
					],
				],
				[
					"type" => "zpassport",
					"fields" => [
						"link",
						"dateCreate",
						"extension",
						"dateModification",
					],
				],
				[
					"type" => "inn",
					"fields" => [
						"link",
						"dateCreate",
						"extension",
						"dateModification",
					],
				],
				[
					"type" => "personalPhoto",
					"fields" => [
						"link",
						"dateCreate",
						"extension",
						"dateModification",
					],
				],
			],
		];
	}

	public function getDecryptor(): ?Decryptor {
		return new Decryptor();
	}

	public function isDecryptable(): bool {
		return isset($this->data["customer"]["signature"]);
	}

}
