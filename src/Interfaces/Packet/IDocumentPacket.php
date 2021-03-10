<?php

namespace BankID\Interfaces\Packet;

interface IDocumentPacket
{

	public function getDocuments(): array;

	public function getDocumentFields(): array;

}
