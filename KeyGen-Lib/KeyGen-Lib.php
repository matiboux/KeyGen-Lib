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
	// Characters sets
	public const NUMERIC_SET = '1234567890';
	public const LOWERCASE_SET = 'abcdefghijklmnopqrstuvwxyz';
	public const UPPERCASE_SET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	public const SPECIAL_SET = '!@#$%^&*()_+-=[]{}|;\':",./<>?';

	// Flags
	public const NUMERIC   = 0b0001;
	public const LOWERCASE = 0b0010;
	public const UPPERCASE = 0b0100;
	public const SPECIAL   = 0b1000;

	// Default Values
	private const DEFAULT_LENGTH = 12;
	private const DEFAULT_FLAGS = self::NUMERIC | self::LOWERCASE | self::UPPERCASE;
	private const DEFAULT_REDUNDANCY = true;

	/** Parameters */
	private static ?int $length = null;
	private static ?int $flags = null;
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
	public static function getFlags(
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
	): string {
		$flags = self::$flags ?? self::DEFAULT_FLAGS;

		if ($numeric !== null) {
			$flags = $numeric ? ($flags | self::NUMERIC) : ($flags & ~self::NUMERIC);
		}
		if ($lowercase !== null) {
			$flags = $lowercase ? ($flags | self::LOWERCASE) : ($flags & ~self::LOWERCASE);
		}
		if ($uppercase !== null) {
			$flags = $uppercase ? ($flags | self::UPPERCASE) : ($flags & ~self::UPPERCASE);
		}
		if ($special !== null) {
			$flags = $special ? ($flags | self::SPECIAL) : ($flags & ~self::SPECIAL);
		}

		return $flags;
	}

	public static function setParams(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null
	): void {
		self::$length = $length ?? self::DEFAULT_LENGTH;
		self::$flags = self::getFlags($numeric, $lowercase, $uppercase, $special);
		self::$redundancy = $redundancy ?? self::DEFAULT_REDUNDANCY;

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
			self::$length = self::DEFAULT_LENGTH;
		}

		self::$flags = self::getFlags($numeric, $lowercase, $uppercase, $special);

		if (isset($redundancy)) {
			self::$redundancy = $redundancy;
		} elseif (!isset(self::$redundancy)) {
			self::$redundancy = self::DEFAULT_REDUNDANCY;
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
			'length' => self::DEFAULT_LENGTH,
			'numeric' => (self::DEFAULT_FLAGS & self::NUMERIC) === self::NUMERIC,
			'lowercase' => (self::DEFAULT_FLAGS & self::LOWERCASE) === self::LOWERCASE,
			'uppercase' => (self::DEFAULT_FLAGS & self::UPPERCASE) === self::UPPERCASE,
			'special' => (self::DEFAULT_FLAGS & self::SPECIAL) === self::SPECIAL,
			'redundancy' => self::DEFAULT_REDUNDANCY,
		];
	}

	/**
	 * @return mixed[]
	 */
	public static function getParams(): array
	{
		$flags = self::$flags ?? self::DEFAULT_FLAGS;

		return [
			'length' => self::$length ?? self::DEFAULT_LENGTH,
			'numeric' => ($flags & self::NUMERIC) === self::NUMERIC,
			'lowercase' => ($flags & self::LOWERCASE) === self::LOWERCASE,
			'uppercase' => ($flags & self::UPPERCASE) === self::UPPERCASE,
			'special' => ($flags & self::SPECIAL) === self::SPECIAL,
			'redundancy' => self::$redundancy ?? self::DEFAULT_REDUNDANCY,
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
		if ((self::$flags & self::NUMERIC) === self::NUMERIC) {
			$charactersSet .= self::NUMERIC_SET;
		}
		if ((self::$flags & self::LOWERCASE) === self::LOWERCASE) {
			$charactersSet .= self::LOWERCASE_SET;
		}
		if ((self::$flags & self::UPPERCASE) === self::UPPERCASE) {
			$charactersSet .= self::UPPERCASE_SET;
		}
		if ((self::$flags & self::SPECIAL) === self::SPECIAL) {
			$charactersSet .= self::SPECIAL_SET;
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
