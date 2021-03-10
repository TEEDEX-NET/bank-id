<?php

namespace BankID\Abstracts\Data;

use BankID\Interfaces\Data\IData;

abstract class AScan extends AData implements IData
{

	protected ?string $type;
	protected ?string $download_url;
	protected ?string $create_date;
	protected ?string $file_extension;
	protected ?string $file_name;
	protected ?string $file_content;

	final public function getDataArray(): array
	{
		return [
			"download_url" => $this->getDownloadUrl(),
			"create_date" => $this->getCreateDate(),
			"file_extension" => $this->getFileExtension(),
			"file_name" => $this->getFileName(),
			"file_content" => $this->getFileContent(),
		];
	}

	public function getType(): ?string
	{
		return $this->type;
	}

	public function getDownloadUrl(): ?string
	{
		return $this->get("download_url");
	}

	public function getCreateDate(): ?string
	{
		return $this->get("create_date");
	}

	public function getFileExtension(): ?string
	{
		return $this->get("file_extension");
	}

	public function getFileName(): ?string
	{
		return $this->file_name;
	}

	public function getFileContent(): ?string
	{
		return $this->file_content;
	}

}
