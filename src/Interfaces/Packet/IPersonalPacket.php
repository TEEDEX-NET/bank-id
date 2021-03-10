<?php

namespace BankID\Interfaces\Packet;

use BankID\Abstracts\Data\APersonal;

interface IPersonalPacket
{

	public function getPersonalData(): APersonal;

	public function getPersonalFields(): array;

}
