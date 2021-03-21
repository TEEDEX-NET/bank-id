<?php

namespace BankID\Providers\nbu\Data;

use BankID\Abstracts\Data\AScan;
use BankID\Interfaces\Packet\IPacket;

class Scan extends AScan
{

	protected ?string $file_content = null;
	protected ?string $file_name = null;

	public function __construct(array $data, IPacket $packet)
	{
		$this->type = $data["type"] ?? null;
		$this->file_content = $data["scanFile"] ?? null;
		$this->create_date = $data["dateCreate"] ?? null;
		$this->file_extension = $data["extension"] ?? null;

		$this->packet = $packet;
	}

}
