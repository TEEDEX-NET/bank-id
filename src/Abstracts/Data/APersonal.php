<?php

namespace BankID\Abstracts\Data;

use BankID\Interfaces\Data\IData;

abstract class APersonal extends AData implements IData
{

	protected ?string $first_name = null;
	protected ?string $middle_name = null;
	protected ?string $last_name = null;
	protected ?string $phone = null;
	protected ?string $itn = null;
	protected ?string $client_id = null;
	protected ?string $description = null;
	protected ?string $birthday = null;
	protected ?string $sex = null;
	protected ?string $email = null;
	protected ?string $resident = null;
	protected ?string $updated = null;
	protected ?string $social_status = null;
	protected ?string $is_pep = null;
	protected ?string $is_terrorist = null;
	protected ?string $is_restriction = null;
	protected ?string $is_top_level_risk = null;
	protected ?string $sidBi = null;
	protected ?string $memberId = null;

	final public function getDataArray(): array
	{
		return [
			"first_name" => $this->getFirstName(),
			"middle_name" => $this->getMiddleName(),
			"last_name" => $this->getLastName(),
			"phone" => $this->getPhone(),
			"itn" => $this->getTaxNumber(),
			"client_id" => $this->getClientId(),
			"description" => $this->getDescription(),
			"birthday" => $this->getBirthday(),
			"sex" => $this->getSex(),
			"email" => $this->getEmail(),
			"resident" => $this->getResident(),
			"updated" => $this->getUpdated(),
			"social_status" => $this->getSocialStatus(),
			"is_pep" => $this->getIsPEP(),
			"is_terrorist" => $this->getIsTerrorist(),
			"is_restriction" => $this->getIsRestriction(),
			"is_top_level_risk" => $this->getIsTopLevelRisk(),
			"sidBi" => $this->getSidBi(),
			"memberId" => $this->getMemberId(),
		];
	}

	public function getFirstName(): ?string
	{
		return $this->get("first_name");
	}

	public function getMiddleName(): ?string
	{
		return $this->get("middle_name");
	}

	public function getLastName(): ?string
	{
		return $this->get("last_name");
	}

	public function getPhone(): ?string
	{
		return $this->get("phone");
	}

	public function getTaxNumber(): ?string
	{
		return $this->get("itn");
	}

	public function getClientId(): ?string
	{
		return $this->get("client_id");
	}

	public function getDescription(): ?string
	{
		return $this->get("description");
	}

	public function getBirthday(): ?string
	{
		return $this->get("birthday");
	}

	public function getSex(): ?string
	{
		return $this->get("sex");
	}

	public function getEmail(): ?string
	{
		return $this->get("email");
	}

	public function getResident(): ?string
	{
		return $this->get("resident");
	}

	public function getUpdated(): ?string
	{
		return $this->get("updated");
	}

	public function getSocialStatus(): ?string {
		return $this->get("social_status");
	}

	public function getIsPEP(): ?string {
		return $this->get("is_pep");
	}

	public function getIsTerrorist(): ?string {
		return $this->get("is_terrorist");
	}

	public function getIsRestriction(): ?string {
		return $this->get("is_restriction");
	}

	public function getIsTopLevelRisk(): ?string {
		return $this->get("is_top_level_risk");
	}

	public function getSidBi(): ?string {
		return $this->get("sidBi");
	}

	public function getMemberId(): ?string {
		return $this->get("memberId");
	}

}
