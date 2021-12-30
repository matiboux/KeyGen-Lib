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
class KeyGenStatic
{
	private static KeyGen $instance;

	public static function getStaticInstance(): KeyGen
	{
		if (!isset(self::$instance))
		{
			self::$instance = new KeyGen();
		}

		return self::$instance;
	}

	public static function getFlags(): int
	{
		return self::getStaticInstance()->getFlags();
	}

	public static function setParams(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null,
	): void {
		self::getStaticInstance()->setParams($length, $numeric, $lowercase, $uppercase, $special, $redundancy);
	}

	public static function updateParams(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null,
	): void {
		self::getStaticInstance()->updateParams($length, $numeric, $lowercase, $uppercase, $special, $redundancy);
	}

	public static function resetParams(): void
	{
		self::getStaticInstance()->resetParams();
	}

	/**
	 * @return mixed[]
	 */
	public static function getParams(): array
	{
		return self::getStaticInstance()->getParams();
	}

	public static function isDefaultParameters(): bool
	{
		return self::getStaticInstance()->isDefaultParameters();
	}

	public static function keygen(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null
	): ?string {
		return self::getStaticInstance()->keygen($length, $numeric, $lowercase, $uppercase, $special, $redundancy);
	}

	public static function isForcedRedundancy(): bool
	{
		return self::getStaticInstance()->isForcedRedundancy();
	}

	public static function isError(): bool
	{
		return self::getStaticInstance()->isError();
	}

	/**
	 * Get Error Infos.
	 *
	 * @return mixed[]|null
	 */
	public static function getErrorInfos(): array|null
	{
		return self::getStaticInstance()->getErrorInfos();
	}

	public static function getErrorId(): int
	{
		return self::getStaticInstance()->getErrorId();
	}

	public static function getErrorCode(): ?string
	{
		return self::getStaticInstance()->getErrorCode();
	}

	public static function getErrorMessage(): ?string
	{
		return self::getStaticInstance()->getErrorMessage();
	}
}
