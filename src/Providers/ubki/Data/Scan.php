<?php

namespace BankID\Providers\ubki\Data;

use BankID\Abstracts\Data\AScan;
use BankID\Interfaces\Packet\IPacket;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Scan extends AScan
{

	protected ?string $file_content = null;
	protected ?string $file_name = null;

	public function __construct(array $data, IPacket $packet)
	{
		$this->type = $data["type"] ?? null;
		$this->download_url = $data["link"] ?? null;
		$this->create_date = $data["dateCreate"] ?? null;
		$this->file_extension = $data["extension"] ?? null;

		$this->packet = $packet;

		$this->loadFile();
	}

	private function loadFile(): void
	{

		$authentication = $this->packet
			->getProvider()
			->getAuthentication();
		$headers = $authentication->getAuthorizationHeaders();
		$url = $this->getDownloadUrl();
		$client = new Client();

		try {
			$response = $client->get($url, [
				"headers" => $headers,
				"verify" => false,
			]);

			$response_body = $response->getBody()->getContents();
			$status_code = $response->getStatusCode();
			$response_header = $response->getHeaders();
			$error = null;

			$this->file_content = base64_encode($response_body);
			$matches = [];
			preg_match(
				"~filename=(.*)$~ui",
				$response_header["Content-Disposition"][0],
				$matches
			);
			$this->file_name = $matches[1];
		} catch (GuzzleException $ex) {
			$response_body = $response_header = $status_code = $data = null;
			$error = $ex->getMessage();
		} finally {
			if ($logger = $authentication->getLogger()) {
				$logger->add("download_{$this->type}_scan", [
					"url" => $url,
					"request_headers" => $authentication
						->getMaskedAuthorizationHeaders(),
					"status_code" => $status_code,
					"response" => base64_encode($response_body),
					"response_headers" => $response_header,
					"error" => $error,
				]);
			}
		}
	}

}
