<?php

namespace BankID\Providers\nbu\Auth;

use BankID\Helpers\BankIDException;
use BankID\Helpers\Logger;
use BankID\Interfaces\Auth\IOAuth2;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OAuth2 implements IOAuth2
{

	private string $url;
	private string $client_id;
	private string $client_secret;
	private string $redirect_uri;
	private string $code;
	private ?string $access_token = null;
	private ?Logger $logger = null;

	public function setCredentials(array $credentials): void
	{
		$this->url = $credentials["url"] ?? "";
		$this->client_id = $credentials["client_id"] ?? "";
		$this->client_secret = $credentials["client_secret"] ?? "";
		$this->redirect_uri = $credentials["redirect_uri"] ?? "";

		if ($this->logger) {
			$this->logger->add("set_auth_credentials", [
				"url" => $this->url,
				"client_id" => $this->client_id,
				"client_secret" => substr_replace(
					$this->client_secret,
					str_repeat("*", mb_strlen($this->client_secret) - 2),
					1,
					mb_strlen($this->client_secret) - 2
				),
				"redirect_uri" => $this->redirect_uri,
			]);
		}
	}

	public function getOAuth2Url(): string
	{
		$query = http_build_query([
			"response_type" => "code",
			"client_id" => $this->client_id,
			"redirect_uri" => $this->redirect_uri,
		]);

		$url = "https://$this->url/v1/bank/oauth2/authorize?$query";

		if ($this->logger) {
			$this->logger->add("get_oauth2_url", [$url]);
		}

		return $url;
	}

	public function setCode(string $code): void
	{
		$this->code = $code;

		if ($this->logger) {
			$this->logger->add("set_auth_code", [$code]);
		}
	}

	public function getAuthorizationHeaders(): array
	{
		$client_id = $this->client_id;
		$access_token = $this->getAccessToken();

		return [
			"Authorization" => "Bearer $access_token",
		];
	}

	public function getMaskedAuthorizationHeaders(): array
	{
		$client_id = $this->client_id;
		$access_token = $this->getAccessToken();
		$masked_access_token = substr_replace(
			$access_token,
			str_repeat("*", mb_strlen($access_token) - 2),
			1,
			mb_strlen($access_token) - 2
		);

		return [
			"Authorization" => "Bearer $masked_access_token",
		];
	}

	public function getLogger(): ?Logger
	{
		return $this->logger;
	}

	public function setLogger(Logger $logger): void {
		$this->logger = $logger;
	}

	public function getUrl(): string {
		return $this->url;
	}

	private function getAccessToken(): string
	{
		if (!$this->access_token) {
			$client = new Client();

			$headers = [
				"Content-Type" => "application/x-www-form-urlencoded",
				"Accept" => "application/json"
			];
			$log_body = $body = [
				"grant_type" => "authorization_code",
				"client_id" => $this->client_id,
				"client_secret" => $this->client_secret,
				"redirect_uri" => $this->redirect_uri,
				"code" => $this->code,
			];
			$error = null;

			try {
				$url = "https://$this->url/v1/bank/oauth2/token";

				$response = $client->post($url, [
					"headers" => $headers,
					"verify" => false,
					"form_params" => $body,
				]);

				$response_body = $response->getBody()->getContents();
				$response_header = $response->getHeaders();
				$status_code = $response->getStatusCode();
				$data = json_decode($response_body, true);
			} catch (GuzzleException $ex) {
				$response_header = $status_code = $data = null;
				$error = $ex->getMessage();
			} finally {
				if ($logger = $this->getLogger()) {
					$log_data = $data;
					$log_data["access_token"] = substr_replace(
						$data["access_token"],
						str_repeat("*", mb_strlen($data["access_token"]) - 2),
						1,
						mb_strlen($data["access_token"]) - 2
					);
					$log_data["refresh_token"] = substr_replace(
						$data["refresh_token"],
						str_repeat("*", mb_strlen($data["refresh_token"]) - 2),
						1,
						mb_strlen($data["refresh_token"]) - 2
					);
					$log_body["client_secret"] = substr_replace(
						$body["client_secret"],
						str_repeat("*", mb_strlen($body["client_secret"]) - 2),
						1,
						mb_strlen($body["client_secret"]) - 2
					);

					$logger->add("get_access_token", [
						"url" => $url,
						"request_headers" => $headers,
						"request" => $log_body,
						"status_code" => $status_code,
						"response" => $log_data,
						"response_headers" => $response_header,
						"error" => $error,
					]);
				}
			}

			if (
				$status_code != "200" ||
				!$data ||
				!isset($data["access_token"])
			) {
				BankIDException::raise("Can't get access token");
			}

			$this->access_token = $data["access_token"];
		}

		return $this->access_token;
	}

}
