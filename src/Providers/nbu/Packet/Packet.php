<?php

namespace BankID\Providers\nbu\Packet;

use BankID\Abstracts\Data\APersonal;
use BankID\Abstracts\Packet\APacket;
use BankID\Interfaces\Packet\IAddressPacket;
use BankID\Interfaces\Packet\IDocumentPacket;
use BankID\Interfaces\Packet\IPersonalPacket;
use BankID\Interfaces\Packet\IScanPacket;
use BankID\Providers\nbu\Data\Address;
use BankID\Providers\nbu\Data\Document;
use BankID\Providers\nbu\Data\Personal;
use BankID\Providers\nbu\Data\Scan;
use BankID\Providers\nbu\Decryptor\Decryptor;

class Packet
	extends APacket
	implements IAddressPacket, IDocumentPacket, IPersonalPacket, IScanPacket
{

	public function setMemberId(string $member_id): void
	{
		$this->data["memberId"] = $member_id;
	}

	public function setSidBi(string $sid_bi): void
	{
		$this->data["sidBi"] = $sid_bi;
	}

	public function setData(array $data): void
	{
		$decryptor = new Decryptor();
		$this->data = $decryptor->decryptEUSign(
			$data["cert"], $data["customerCrypto"]
		);
	}

	public function getPersonalData(): APersonal
	{
		return new Personal($this->data, $this);
	}

	public function getDocuments(): array
	{
		$data = [];

		foreach ($this->data["documents"] as $document) {
			$obj = new Document($document, $this);
			$data[$obj->getType()] = $obj;
		}

		return $data;
	}

	public function getAddresses(): array
	{
		$data = [];

		foreach ($this->data["addresses"] as $address) {
			$obj = new Address($address, $this);
			$data[$obj->getType()] = $obj;
		}

		return $data;
	}

	public function getScans(): array
	{
		$data = [];

		foreach ($this->data["scans"] as $scan) {
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
						"typeName",
						"series",
						"number",
						"issue",
						"dateIssue",
						"dateExpiration",
						"issueCountryIso2",
					],
				],
				[
					"type" => "zpassport",
					"fields" => [
						"typeName",
						"series",
						"number",
						"issue",
						"dateIssue",
						"dateExpiration",
						"issueCountryIso2",
					],
				],
				[
					"type" => "ident",
					"fields" => [
						"typeName",
						"series",
						"number",
						"issue",
						"dateIssue",
						"dateExpiration",
						"issueCountryIso2",
					],
				],
				[
					"type" => "idpassport",
					"fields" => [
						"typeName",
						"number",
						"issue",
						"dateIssue",
						"dateExpiration",
						"issueCountryIso2",
					],
				],
			],
		];
	}

	public function getPersonalFields(): array
	{
		return [
			"fields" => [
				"lastName",
				"firstName",
				"middleName",
				"phone",
				"inn",
				"clId",
				"clIdText",
				"birthDay",
				"sex",
				"email",
				"socStatus",
				"flagPEPs",
				"flagPersonTerror",
				"flagRestriction",
				"flagTopLevelRisk",
				"uaResident",
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
						"scanFile",
						"dateCreate",
						"extension",
					],
				],
				[
					"type" => "idpassport",
					"fields" => [
						"scanFile",
						"dateCreate",
						"extension",
					],
				],
				[
					"type" => "zpassport",
					"fields" => [
						"scanFile",
						"dateCreate",
						"extension",
					],
				],
				[
					"type" => "inn",
					"fields" => [
						"scanFile",
						"dateCreate",
						"extension",
					],
				],
				[
					"type" => "personalPhoto",
					"fields" => [
						"scanFile",
						"dateCreate",
						"extension",
					],
				],
			],
		];
	}

	public function getDecryptor(): ?Decryptor {
		return null;
	}

	public function isDecryptable(): bool {
		return false;
	}

}
