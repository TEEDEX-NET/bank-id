<?php

namespace BankID\Interfaces\Provider;

use BankID\Abstracts\Packet\APacket;

interface IProvider
{

	public static function getAuthClasses(): array;

	public function getPacket(): APacket;

	public function loadData(): void;

}
