<?php

namespace BankID\Abstracts\Data;

use BankID\Interfaces\Data\IData;

abstract class ADocument extends AData implements IData
{

	protected ?string $type = null;
	protected ?string $name = null;
	protected ?string $series = null;
	protected ?string $number = null;
	protected ?string $record_number = null;
	protected ?string $issuer = null;
	protected ?string $issue_date = null;
	protected ?string $expire_date = null;
	protected ?string $issuer_country = null;

	final public function getDataArray(): array
	{
		return [
			"name" => $this->getName(),
			"series" => $this->getSeries(),
			"number" => $this->getNumber(),
			"record_number" => $this->getRecordNumber(),
			"issuer" => $this->getIssuer(),
			"issue_date" => $this->getIssueDate(),
			"expire_date" => $this->getExpireDate(),
			"issuer_country" => $this->getIssuerCountry(),
		];
	}

	public function getName(): ?string
	{
		return $this->get("name");
	}

	public function getType(): ?string
	{
		return $this->type;
	}

	public function getSeries(): ?string
	{
		return $this->get("series");
	}

	public function getNumber(): ?string
	{
		return $this->get("number");
	}

	public function getRecordNumber(): ?string
	{
		return $this->get("record_number");
	}

	public function getIssuer(): ?string
	{
		return $this->get("issuer");
	}

	public function getIssueDate(): ?string
	{
		return $this->get("issue_date");
	}

	public function getExpireDate(): ?string
	{
		return $this->get("expire_date");
	}

	public function getIssuerCountry(): ?string
	{
		return $this->get("issuer_country");
	}

}
