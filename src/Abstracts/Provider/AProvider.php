<?php

namespace BankID\Abstracts\Provider;

use BankID\Abstracts\Packet\APacket;
use BankID\Interfaces\Auth\IAuth;
use BankID\Interfaces\Packet\IAddressPacket;
use BankID\Interfaces\Packet\IDocumentPacket;
use BankID\Interfaces\Packet\IPersonalPacket;
use BankID\Interfaces\Packet\IScanPacket;
use BankID\Interfaces\Provider\IProvider;

abstract class AProvider implements IProvider
{

	protected IAuth $authentication;
	protected ?APacket $packet = null;
	protected string $processing_url;

	final public function __construct(
		IAuth $authentication, string $processing_url
	)
	{
		$this->authentication = $authentication;
		$this->processing_url = $processing_url;
	}

	public function getAuthentication(): IAuth {
		return $this->authentication;
	}

	final public function getData(): array
	{
		$data = [];

		$this->loadData();
		$packet = $this->getPacket();

		if ($packet instanceof IAddressPacket) {
			$data["addresses"] = $packet->getAddresses();
		}

		if ($packet instanceof IDocumentPacket) {
			$data["documents"] = $packet->getDocuments();
		}

		if ($packet instanceof IPersonalPacket) {
			$data["fields"] = $packet->getPersonalData();
		}

		if ($packet instanceof IScanPacket) {
			$data["scans"] = $packet->getScans();
		}

		return $data;
	}

	final protected function getRequestedFields(): array
	{
		$data = [];

		$packet = $this->getPacket();

		if ($packet instanceof IAddressPacket) {
			$data += $packet->getAddressFields();
		}

		if ($packet instanceof IDocumentPacket) {
			$data += $packet->getDocumentFields();
		}

		if ($packet instanceof IPersonalPacket) {
			$data += $packet->getPersonalFields();
		}

		if ($packet instanceof IScanPacket) {
			$data += $packet->getScanFields();
		}

		return $data;
	}

	public function getUrl(): string {
		return $this->authentication->getUrl();
	}

}
