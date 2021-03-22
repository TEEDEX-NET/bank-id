<?php

namespace BankID\Abstracts\Data;

use BankID\Interfaces\Data\IData;

abstract class AAddress extends AData implements IData
{

	protected ?string $type = null;
	protected ?string $country = null;
	protected ?string $state = null;
	protected ?string $area = null;
	protected ?string $city = null;
	protected ?string $district = null;
	protected ?string $street = null;
	protected ?string $home = null;
	protected ?string $apartment = null;

	final public function getDataArray(): array
	{
		return [
			"country" => $this->getCountry(),
			"state" => $this->getState(),
			"area" => $this->getArea(),
			"city" => $this->getCity(),
			"district" => $this->getDistrict(),
			"street" => $this->getStreet(),
			"home" => $this->getHome(),
			"apartment" => $this->getApartment(),
		];
	}

	public function getType(): ?string
	{
		return $this->type;
	}

	public function getCountry(): ?string
	{
		return $this->get("country");
	}

	public function getState(): ?string
	{
		return $this->get("state");
	}

	public function getArea(): ?string
	{
		return $this->get("area");
	}

	public function getCity(): ?string
	{
		return $this->get("city");
	}

	public function getDistrict(): ?string
	{
		return $this->get("district");
	}

	public function getStreet(): ?string
	{
		return $this->get("street");
	}

	public function getHome(): ?string
	{
		return $this->get("home");
	}

	public function getApartment(): ?string
	{
		return $this->get("apartment");
	}

}
