<?php

namespace BankID\Providers\nbu;

use BankID\Abstracts\Packet\APacket;
use BankID\Abstracts\Provider\AProvider;
use BankID\Helpers\BankIDException;
use BankID\Interfaces\Auth\IAuth;
use BankID\Providers\nbu\Auth\OAuth2;
use BankID\Providers\nbu\Packet\Packet;
use BankID\Providers\nbu\Decryptor\Decryptor;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class NBU extends AProvider
{

	/**
	 * @return array<string, IAuth>
	 */
	public static function getAuthClasses(): array
	{
		return [
			"OAuth2" => OAuth2::class,
			"default" => OAuth2::class,
		];
	}

	public function loadData(): void
	{
		$decryptor = new Decryptor();

		$auth_headers = $this->authentication->getAuthorizationHeaders();
		$headers = [
			"Content-Type" => "application/json",
			"Accept" => "application/json",
		] + $auth_headers;
		$body = [
			"type" => "physical",
			"cert" => $decryptor->getPublicCert(),
		] + $this->getRequestedFields();

		$client = new Client();

		try {
			$url = "https://{$this->processing_url}".
				"/v1/bank/resource/client";

			$response = $client->post($url, [
				"headers" => $headers,
				"body" => json_encode($body),
				"verify" => false,
			]);

			$response_body = $response->getBody()->getContents();
			$status_code = $response->getStatusCode();
			$data = json_decode($response_body, true);
			$response_header = $response->getHeaders();
			$error = null;
		} catch (GuzzleException $ex) {
			$response_header = $status_code = $data = null;
			$error = $ex->getMessage();
		} finally {
			if ($logger = $this->authentication->getLogger()) {
				$logger->add("load_data", [
					"url" => $url,
					"request_headers" => [
						"Content-Type" => "application/json",
						"Accept" => "application/json",
					] + $this->authentication->getMaskedAuthorizationHeaders(),
					"request" => $body,
					"status_code" => $status_code,
					"response" => $data,
					"response_headers" => $response_header,
					"error" => $error,
				]);
			}
		}

		if ($data && isset($data) && $data["state"] == "ok") {
			$packet = $this->getPacket();
			$packet->setData($data);
			$packet->setMemberId($data["memberId"]);
			$packet->setSidBi($data["sidBi"]);
		} else {
			BankIDException::raise("Can't load data");
		}
	}

	public function getPacket(): APacket
	{
		if (!$this->packet) {
			$this->packet = new Packet($this);
		}

		return $this->packet;
	}

}
