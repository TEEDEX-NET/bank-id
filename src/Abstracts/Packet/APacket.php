<?php

namespace BankID\Abstracts\Packet;

use BankID\Interfaces\Packet\IPacket;
use BankID\Interfaces\Provider\IProvider;

abstract class APacket implements IPacket
{

	protected array $data;
	private IProvider $provider;

	public function __construct(IProvider $provider) {
		$this->provider = $provider;
	}

	public function getProvider(): IProvider {
		return $this->provider;
	}

	public function setData(array $data): void
	{
		$this->data = $data;
	}

}
