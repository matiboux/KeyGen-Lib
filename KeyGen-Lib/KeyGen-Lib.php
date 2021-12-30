<?php

/**
 * This file is part of KeyGen Library.
 *
 * KeyGen Library is an open source random password generator PHP library.
 * This project is directly related to matiboux's KeyGen project.
 *
 * KeyGen Library Github repository: https://github.com/matiboux/KeyGen-Lib
 *
 * Creator & Developer: Matiboux (Mathieu Guérin)
 *   → Github: https://github.com/matiboux
 *   → Email: matiboux@gmail.com
 *
 * MIT License
 * Copyright (C) 2017 Matiboux (Mathieu Guérin)
 * You'll find a copy of the MIT license in the LICENSE file.
 *
 * @author Matiboux <matiboux@gmail.com>
 *
 * @copyright 2017 Matiboux (Mathieu Guérin)
 * @license https://opensource.org/licenses/MIT
 *
 * @version 1.2.0
 *
 * Releases date:
 * - v1.0.0: April 15, 2017
 * - v1.0.1: April 17, 2017
 * - v1.1.0: Sept. 24, 2018
 * - v1.2.0: WIP
 */

namespace KeyGenLib;

/**
 * @author Matiboux <matiboux@gmail.com>
 */
class KeyGen
{
	/** Character Sets */
	public const NUMERIC = '1234567890';
	public const LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
	public const UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	public const SPECIAL = '!#$%&\()+-;?@[]^_{|}';

	/** Default Values */
	private static int $defaultLength = 12;
	private static bool $defaultNumeric = true;
	private static bool $defaultLowercase = true;
	private static bool $defaultUppercase = true;
	private static bool $defaultSpecial = false;
	private static bool $defaultRedundancy = true;

	/** Parameters */
	private static ?int $length = null;
	private static ?bool $numeric = null;
	private static ?bool $lowercase = null;
	private static ?bool $uppercase = null;
	private static ?bool $special = null;
	private static ?bool $redundancy = null;

	/** Indicators */
	private static bool $defaultParameters = true;
	private static bool $forcedRedundancy = false;

	/**
	 * Error.
	 *
	 * @var mixed[]|null
	 */
	private static ?array $lastError = null;

	/** ----------------------- */
	/**  Parameters Management  */
	/** ----------------------- */
	public static function setParams(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null
	): void {
		self::$length = isset($length) ? $length : self::$defaultLength;
		self::$numeric = isset($numeric) ? $numeric : self::$defaultNumeric;
		self::$lowercase = isset($lowercase) ? $lowercase : self::$defaultLowercase;
		self::$uppercase = isset($uppercase) ? $uppercase : self::$defaultUppercase;
		self::$special = isset($special) ? $special : self::$defaultSpecial;
		self::$redundancy = isset($redundancy) ? $redundancy : self::$defaultRedundancy;

		if (self::getParams() == self::getDefaultParams()) {
			self::$defaultParameters = true;
		} else {
			self::$defaultParameters = false;
		}
	}

	public static function updateParams(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null
	): void {
		if (isset($length)) {
			self::$length = $length;
		} elseif (!isset(self::$length)) {
			self::$length = self::$defaultLength;
		}
		if (isset($numeric)) {
			self::$numeric = $numeric;
		} elseif (!isset(self::$numeric)) {
			self::$numeric = self::$defaultNumeric;
		}
		if (isset($lowercase)) {
			self::$lowercase = $lowercase;
		} elseif (!isset(self::$lowercase)) {
			self::$lowercase = self::$defaultLowercase;
		}
		if (isset($uppercase)) {
			self::$uppercase = $uppercase;
		} elseif (!isset(self::$uppercase)) {
			self::$uppercase = self::$defaultUppercase;
		}
		if (isset($special)) {
			self::$special = $special;
		} elseif (!isset(self::$special)) {
			self::$special = self::$defaultSpecial;
		}
		if (isset($redundancy)) {
			self::$redundancy = $redundancy;
		} elseif (!isset(self::$redundancy)) {
			self::$redundancy = self::$defaultRedundancy;
		}

		if (self::getParams() == self::getDefaultParams()) {
			self::$defaultParameters = true;
		} else {
			self::$defaultParameters = false;
		}
	}

	public static function resetParams(): void
	{
		self::setParams();
	}

	/**
	 * @return mixed[]
	 */
	public static function getDefaultParams(): array
	{
		return [
			'length' => self::$defaultLength,
			'numeric' => self::$defaultNumeric,
			'lowercase' => self::$defaultLowercase,
			'uppercase' => self::$defaultUppercase,
			'special' => self::$defaultSpecial,
			'redundancy' => self::$defaultRedundancy,
		];
	}

	/**
	 * @return mixed[]
	 */
	public static function getParams(): array
	{
		return [
			'length' => isset(self::$length) ? self::$length : self::$defaultLength,
			'numeric' => isset(self::$numeric) ? self::$numeric : self::$defaultNumeric,
			'lowercase' => isset(self::$lowercase) ? self::$lowercase : self::$defaultLowercase,
			'uppercase' => isset(self::$uppercase) ? self::$uppercase : self::$defaultUppercase,
			'special' => isset(self::$special) ? self::$special : self::$defaultSpecial,
			'redundancy' => isset(self::$redundancy) ? self::$redundancy : self::$defaultRedundancy,
		];
	}

	public static function isDefaultParameters(): bool
	{
		return self::$defaultParameters;
	}

	/** ---------------------- */
	/**  KeyGen Core functions */
	/** ---------------------- */

	/** Keygen Generator */
	public static function keygen(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null
	): ?string {
		self::clearError();
		self::updateParams($length, $numeric, $lowercase, $uppercase, $special, $redundancy);

		$charactersSet = '';
		if (self::$numeric) {
			$charactersSet .= self::NUMERIC;
		}
		if (self::$lowercase) {
			$charactersSet .= self::LOWERCASE;
		}
		if (self::$uppercase) {
			$charactersSet .= self::UPPERCASE;
		}
		if (self::$special) {
			$charactersSet .= self::SPECIAL;
		}

		if (empty(self::$length)) {
			self::setError('ERR_EMPTY_LENGTH');
		} elseif (self::$length < 0) {
			self::setError('ERR_NEGATIVE_LENGTH');
		} elseif (!is_numeric(self::$length)) {
			self::setError('ERR_LENGTH_NOT_NUMERIC');
		} elseif (empty($charactersSet)) {
			self::setError('ERR_EMPTY_CHARACTERS_SET');
		}

		if (!self::isError()) {
			if (self::$length > strlen($charactersSet) && !self::$redundancy) {
				self::$forcedRedundancy = true;
			} else {
				self::$forcedRedundancy = false;
			}

			$keygen = '';
			while (strlen($keygen) < self::$length) {
				$randomCharacter = substr($charactersSet, mt_rand(0, strlen($charactersSet) - 1), 1);
				if (self::$redundancy || self::$forcedRedundancy || !strstr($keygen, $randomCharacter)) {
					$keygen .= $randomCharacter;
				}
			}

			return $keygen;
		} else {
			return null;
		}
	}

	public static function isForcedRedundancy(): bool
	{
		return self::$forcedRedundancy;
	}

	/** ------------------ */
	/**  Error Management  */
	/** ------------------ */

	/** Set Error */
	private static function setError(int|string $id): void
	{
		if (array_search($id, $errorInfos = ['id' => 2, 'code' => 'ERR_EMPTY_LENGTH', 'message' => 'The keygen length parameter cannot be empty.'])) {
			self::$lastError = $errorInfos;
		} elseif (array_search($id, $errorInfos = ['id' => 3, 'code' => 'ERR_NEGATIVE_LENGTH', 'message' => 'The keygen length parameter cannot be negative.'])) {
			self::$lastError = $errorInfos;
		} elseif (array_search($id, $errorInfos = ['id' => 4, 'code' => 'ERR_LENGTH_NULL', 'message' => 'The keygen length parameter cannot be null.'])) {
			self::$lastError = $errorInfos;
		} elseif (array_search($id, $errorInfos = ['id' => 5, 'code' => 'ERR_EMPTY_CHARACTERS_SET', 'message' => 'The character set cannot be empty.'])) {
			self::$lastError = $errorInfos;
		} elseif (array_search($id, $errorInfos = ['id' => 6, 'code' => 'ERR_LENGTH_NOT_NUMERIC', 'message' => 'The keygen length parameter must be numeric.'])) {
			self::$lastError = $errorInfos;
		} else {
			self::$lastError = ['id' => 1, 'code' => 'ERR_UNKNOWN', 'message' => 'An unknown error occurred.'];
		}
	}

	private static function clearError(): void
	{
		self::$lastError = null;
	}

	public static function isError(): bool
	{
		return null !== self::$lastError;
	}

	/**
	 * Get Error Infos.
	 *
	 * @return mixed[]|null
	 */
	public static function getErrorInfos(): array|null
	{
		return self::$lastError ?? null;
	}

	public static function getErrorId(): int
	{
		return null !== self::$lastError ? self::$lastError['id'] : 0;
	}

	public static function getErrorCode(): ?string
	{
		return null !== self::$lastError ? self::$lastError['code'] : null;
	}

	public static function getErrorMessage(): ?string
	{
		return null !== self::$lastError ? self::$lastError['message'] : null;
	}
}
