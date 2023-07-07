<?php

use App\ValuesObject\SecretKeyGenerator;

if ( ! function_exists('getPlatformUuid')) {
	/*** @return string */
	function getPlatformUuid(): string
	{
		return config('app.platform_uuid');
	}
}
if ( ! function_exists('uuid')) {
	/*** @return string */
	function uuid(): string
	{
		return Str::uuid();
	}
}
if ( ! function_exists('getSecretToken')) {
	/*** @return string|NULL */
	function getSecretToken(): ?string
	{
		$platform = getPlatform();
		return $platform === NULL ? NULL : $platform->getSecretToken();
	}
}
if ( ! function_exists('generateSecretKey')) {
	/**
	 * @param int|NULL    $keySize
	 * @param bool|NULL   $finallyEncoding
	 * @param string|NULL $hashAlgorithm
	 * @return string
	 */
	function generateSecretKey(?int $keySize = NULL, ?bool $finallyEncoding = NULL, ?string $hashAlgorithm = NULL): string
	{
		$generator = new SecretKeyGenerator();
		if ($keySize) {
			$generator->setKeySize($keySize);
		}
		if ($finallyEncoding !== NULL) {
			$generator->setFinallyEncoding($finallyEncoding);
		}
		if ($hashAlgorithm) {
			$generator->setHashAlgorithm($hashAlgorithm);
		}
		return $generator->run();
	}
}