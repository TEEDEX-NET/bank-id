<?php

namespace BankID\Providers\nbu\Decryptor;

use BankID\Helpers\BankIDException;
use BankID\Interfaces\Decryptor\IDecryptor;

class Decryptor implements IDecryptor {

	private string $cert_file;
	private string $public_cert_file;
	private string $cert_pass;

	public function __construct() {
		$this->cert_file = realpath(__DIR__).DIRECTORY_SEPARATOR."..".
			DIRECTORY_SEPARATOR."cert".DIRECTORY_SEPARATOR."key.dat";
		$this->public_cert_file = realpath(__DIR__).DIRECTORY_SEPARATOR.
			"..".DIRECTORY_SEPARATOR."cert".DIRECTORY_SEPARATOR."public.cer";
		$this->cert_pass = file_get_contents(
			realpath(__DIR__).DIRECTORY_SEPARATOR."..".
			DIRECTORY_SEPARATOR."cert".DIRECTORY_SEPARATOR."key.password"
		);
	}

	public function decrypt(?string $string): ?string {
		return null;
	}

	public function getPublicCert(): string {
		$fp = fopen ($this->public_cert_file, "r");
		$cert = fread($fp, 8192);
		fclose($fp);

		return base64_encode($cert);
	}

	/** Dummy legacy code, I'm sorry */
	public function decryptEUSign(string $cert, string $data): array {
		$iErrorCode = 0;
		$iEncoding = 1251;

		$bIsPrivateKeyReaded = false;

		$context = "";
		$pkContext = "";
		$spDevData = "";

		$signerCert = base64_decode($cert);
		$sEnv = base64_decode($data);

		$sPrivateKey = $this->cert_file;
		$sPrivateKeyPassword = $this->cert_pass;

		$bIsTSPUse = 0;
		$sSignTime = "";
		$spIssuer = "";
		$spIssuerCN = "";
		$spSerial = "";
		$spSubject = "";
		$spSubjCN = "";
		$spSubjOrg = "";
		$spSubjOrgUnit = "";
		$spSubjTitle = "";
		$spSubjState = "";
		$spSubjLocality = "";
		$spSubjFullName = "";
		$spSubjAddress = "";
		$spSubjPhone = "";
		$spSubjEMail = "";
		$spSubjDNS = "";
		$spSubjEDRPOUCode = "";
		$spSubjDRFOCode = "";

		$iResult = euspe_setcharset($iEncoding);
		$this->handleResult("SetCharset", $iResult, 0);

		$iResult = euspe_init($iErrorCode);
		$this->handleResult("Initialize", $iResult, $iErrorCode);

		$sFileStorePath = "";
		$bCheckCRLs = false;
		$bAutoRefresh = false;
		$bOwnCRLsOnly = false;
		$bFullAndDeltaCRLs = false;
		$bAutoDownloadCRLs = false;
		$bSaveLoadedCerts = false;
		$iExpireTime = 0;

		$iResult = euspe_getfilestoresettings(
			$sFileStorePath,
			$bCheckCRLs,
			$bAutoRefresh,
			$bOwnCRLsOnly,
			$bFullAndDeltaCRLs,
			$bAutoDownloadCRLs,
			$bSaveLoadedCerts,
			$iExpireTime,
			$iErrorCode
		);
		$this->handleResult("GetFileStoreSettings", $iResult, $iErrorCode);

		$iResult = euspe_readprivatekeyfile(
			$sPrivateKey, $sPrivateKeyPassword, $iErrorCode
		);
		$this->handleResult("ReadPrivateKeyFile", $iResult, $iErrorCode);

		$iResult = euspe_isprivatekeyreaded($bIsPrivateKeyReaded, $iErrorCode);
		$this->handleResult("IssetPrivateKeyFile", $iResult, $iErrorCode);

		$iResult = euspe_ctxcreate($context, $iErrorCode);
		$this->handleResult("CtxCreate", $iResult, $iErrorCode);

		$iResult = euspe_ctxreadprivatekeyfile(
			$context,
			$sPrivateKey,
			$sPrivateKeyPassword,
			$pkContext,
			$iErrorCode
		);
		$this->handleResult("CtxReadPrivateKeyFile", $iResult, $iErrorCode);

		$iResult = euspe_ctxdevelopdata(
			$pkContext,
			$sEnv,
			$signerCert,
			$spDevData,
			$sSignTime,
			$bIsTSPUse,
			$spIssuer,
			$spIssuerCN,
			$spSerial,
			$spSubject,
			$spSubjCN,
			$spSubjOrg,
			$spSubjOrgUnit,
			$spSubjTitle,
			$spSubjState,
			$spSubjLocality,
			$spSubjFullName,
			$spSubjAddress,
			$spSubjPhone,
			$spSubjEMail,
			$spSubjDNS,
			$spSubjEDRPOUCode,
			$spSubjDRFOCode,
			$iErrorCode
		);
		$this->handleResult("CtxDevelopData", $iResult, $iErrorCode);

		$Sign = $spDevData;
		$sSignsCount = 0;
		$signerInfo = null;
		$eutimeinfo_info = null;
		$signerCert2 = "";
		$sSigner = "";

		$iResult = euspe_getsignscount($Sign, $sSignsCount, $iErrorCode);
		$this->handleResult("GetSignsCount", $iResult, $iErrorCode);

		for ($i = 0; $i < $sSignsCount; $i++)
		{
			$iResult = euspe_getsignerinfoex(
				$i, $Sign, $signerInfo, $signerCert2, $iErrorCode
			);
			$this->handleResult("GetSignerInfoEx", $iResult, $iErrorCode);

			$iResult = euspe_getsigntimeinfo(
				$i, $Sign,	$eutimeinfo_info, $iErrorCode
			);
			$this->handleResult("GetSignTimeInfo", $iResult, $iErrorCode);

			$iResult = euspe_getsigner(
				$i, $Sign, $sSigner, $iErrorCode
			);
			$this->handleResult("GetSigner", $iResult, $iErrorCode);


			$iResult = euspe_appendvalidationdatatosigner(
				$sSigner, $signerCert, $sSigner, $iErrorCode
			);
			$this->handleResult(
				"AppendValidationDataToSigner", $iResult, $iErrorCode
			);

		}

		return $this->signVerify($Sign);
	}

	private function signVerify(string $Sign): array {
		$bIsTSPUse = 0;
		$sSignTime = "";
		$spIssuer = "";
		$spIssuerCN = "";
		$spSerial = "";
		$spSubject = "";
		$spSubjCN = "";
		$spSubjOrg = "";
		$spSubjOrgUnit = "";
		$spSubjTitle = "";
		$spSubjState = "";
		$spSubjLocality = "";
		$spSubjFullName = "";
		$spSubjAddress = "";
		$spSubjPhone = "";
		$spSubjEMail = "";
		$spSubjDNS = "";
		$spSubjEDRPOUCode = "";
		$spSubjDRFOCode = "";
		$sVerData = "";
		$iErrorCode = 0;

		$iResult = euspe_signverify(
			$Sign,
			$sSignTime,
			$bIsTSPUse,
			$spIssuer,
			$spIssuerCN,
			$spSerial,
			$spSubject,
			$spSubjCN,
			$spSubjOrg,
			$spSubjOrgUnit,
			$spSubjTitle,
			$spSubjState,
			$spSubjLocality,
			$spSubjFullName,
			$spSubjAddress,
			$spSubjPhone,
			$spSubjEMail,
			$spSubjDNS,
			$spSubjEDRPOUCode,
			$spSubjDRFOCode,
			$sVerData,
			$iErrorCode
		);

		$this->handleResult("VerifySign (internal)", $iResult, $iErrorCode);

		euspe_finalize();

		if($decoded_data = json_decode($sVerData, true)){
			return $decoded_data;
		}else{
			return [];
		}
	}

	private function handleResult(
		string $sMsg, int $iResult, int $iErrorCode
	): void {
		$sErrorDescription = "";
		$bError = ($iResult != 0);

		if ($bError) {
			euspe_geterrdescr($iErrorCode, $sErrorDescription);

			$err_description = iconv(
				"Windows-1251", "UTF-8", $sErrorDescription
			);

			$sResultMsg = "Error, result code - $iResult".
				", error code - $iErrorCode: $err_description";

			throw new BankIDException("EUSignPHP: $sMsg - $sResultMsg");
		}

	}

}
