<?php

namespace App\ValuesObject;

use Exception;

/**
 * Class SecretKeyGenerator
 * @package App\ValueObject
 */
class SecretKeyGenerator
{
	/*** @var int */
	public int $keySize = 32;
	/*** @var string */
	public string $hashAlgorithm = 'sha256';
	/*** @var bool */
	public bool $finallyEncoding = TRUE;
	/*** @var string */
	public string $encodingAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';

	/*** @return int */
	public function getKeySize(): int
	{
		return $this->keySize;
	}

	/**
	 * @param int $keySize
	 * @return void
	 */
	public function setKeySize(int $keySize): void
	{
		$this->keySize = $keySize;
	}

	/*** @return string */
	public function getHashAlgorithm(): string
	{
		return $this->hashAlgorithm;
	}

	/**
	 * @param string $hashAlgorithm
	 * @return void
	 */
	public function setHashAlgorithm(string $hashAlgorithm): void
	{
		$this->hashAlgorithm = $hashAlgorithm;
	}

	/*** @return bool */
	public function isFinallyEncoding(): bool
	{
		return $this->finallyEncoding;
	}

	/*** @param bool $finallyEncoding */
	public function setFinallyEncoding(bool $finallyEncoding): void
	{
		$this->finallyEncoding = $finallyEncoding;
	}

	/*** @return string|NULL */
	public function run(): ?string
	{
		try {
			$key = "";
			for ($i = 0; $i < $this->getKeySize(); $i++) {
				$key .= $this->encodingAlphabet[random_int(0, strlen($this->encodingAlphabet)) - 1];
			}
			return $key;
		} catch (Exception $e) {
			return NULL;
		}
	}
}