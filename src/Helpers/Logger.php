<?php

namespace BankID\Helpers;

class Logger {

	private array $storage = [];

	public function add(string $key, array $text): void {
		$this->storage[$key] = $text;
	}

	public function get(string $key): ?array {
		return $this->storage[$key] ?? null;
	}

	public function getAll(): array {
		return $this->storage;
	}

}
