<?php

namespace BankID\Interfaces\Packet;

interface IScanPacket
{

	public function getScans(): array;

	public function getScanFields(): array;

}
