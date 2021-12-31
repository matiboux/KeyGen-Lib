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
	#region Static properties

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

	// Configuration mode
	public const CURRENT_AS_FALLBACK = false;
	public const DEFAULT_AS_FALLBACK = true;

	// Default Values
	private const DEFAULT_LENGTH = 12;
	private const DEFAULT_FLAGS = self::NUMERIC | self::LOWERCASE | self::UPPERCASE;
	private const DEFAULT_REDUNDANCY = true;

	public const DEFAULT_PARAMS = [
		'length' => self::DEFAULT_LENGTH,
		'numeric' => (self::DEFAULT_FLAGS & self::NUMERIC) === self::NUMERIC,
		'lowercase' => (self::DEFAULT_FLAGS & self::LOWERCASE) === self::LOWERCASE,
		'uppercase' => (self::DEFAULT_FLAGS & self::UPPERCASE) === self::UPPERCASE,
		'special' => (self::DEFAULT_FLAGS & self::SPECIAL) === self::SPECIAL,
		'redundancy' => self::DEFAULT_REDUNDANCY,
	];

	#endregion

	#region Properties

	// Parameters
	private int $length = self::DEFAULT_LENGTH;
	private int $flags = self::DEFAULT_FLAGS;
	private bool $redundancy = self::DEFAULT_REDUNDANCY;

	// Indicators
	private bool $forcedRedundancy = false;

	/**
	 * Last error
	 *
	 * @var mixed[]|null
	 */
	private ?array $lastError = null;

	#endregion

	#region Configuration

	#region Getters	/ Setters

	/**
	 * Get length
	 */
	public function getLength(): int
	{
		return $this->length;
	}

	/**
	 * Set length
	 */
	public function setLength(?int $length, bool $useDefaultOnNull = true): static
	{
		$this->length = $length ?? ($useDefaultOnNull ? self::DEFAULT_LENGTH : $this->length);
		return $this;
	}

	/**
	 * Get flags
	 */
	public function getFlags(): int
	{
		return $this->flags;
	}

	/**
	 * Set flags
	 */
	public function setFlags(int $flags): static
	{
		$this->flags = $flags;
		return $this;
	}

	/**
	 * Add flags
	 */
	public function addFlags(int $flags): static
	{
		$this->flags |= $flags;
		return $this;
	}

	/**
	 * Remove flags
	 */
	public function removeFlags(int $flags): static
	{
		$this->flags &= ~$flags;
		return $this;
	}

	/**
	 * Get redundancy
	 */
	public function getRedundancy(): bool
	{
		return $this->redundancy;
	}

	/**
	 * Set redundancy
	 */
	public function setRedundancy(?bool $redundancy, bool $useDefaultOnNull = true): static
	{
		$this->redundancy = $redundancy ?? ($useDefaultOnNull ? self::DEFAULT_REDUNDANCY : $this->redundancy);
		return $this;
	}

	#endregion

	#region Configuration methods

	public function computeFlags(
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
	): int
	{
		$flags = 0;
		if ($numeric) {
			$flags |= self::NUMERIC;
		}
		if ($lowercase) {
			$flags |= self::LOWERCASE;
		}
		if ($uppercase) {
			$flags |= self::UPPERCASE;
		}
		if ($special) {
			$flags |= self::SPECIAL;
		}

		return $flags;
	}

	public function applyFlags(
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
	): int
	{
		$flags = $this->flags;
		if ($numeric !== null) {
			$flags = $numeric ? ($flags | $this->NUMERIC) : ($flags & ~$this->NUMERIC);
		}
		if ($lowercase !== null) {
			$flags = $lowercase ? ($flags | $this->LOWERCASE) : ($flags & ~$this->LOWERCASE);
		}
		if ($uppercase !== null) {
			$flags = $uppercase ? ($flags | $this->UPPERCASE) : ($flags & ~$this->UPPERCASE);
		}
		if ($special !== null) {
			$flags = $special ? ($flags | $this->SPECIAL) : ($flags & ~$this->SPECIAL);
		}

		return $flags;
	}

	public function getCharactersSet(?int $flags = null): string
	{
		if ($flags === null) {
			$flags = $this->flags;
		}

		$set = '';
		if (($flags & self::NUMERIC) === self::NUMERIC) {
			$set .= self::NUMERIC_SET;
		}
		if (($flags & self::LOWERCASE) === self::LOWERCASE) {
			$set .= self::LOWERCASE_SET;
		}
		if (($flags & self::UPPERCASE) === self::UPPERCASE) {
			$set .= self::UPPERCASE_SET;
		}
		if (($flags & self::SPECIAL) === self::SPECIAL) {
			$set .= self::SPECIAL_SET;
		}

		return $set;
	}

	public function setParams(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null,
		bool $resetConfig = true,
	): void
	{
		$this->setLength($length, $resetConfig);

		$this->setFlags(
			$resetConfig
			? $this->computeFlags($numeric, $lowercase, $uppercase, $special)
			: $this->applyFlags($numeric, $lowercase, $uppercase, $special)
		);

		$this->setRedundancy($redundancy, $resetConfig);
	}

	public function updateParams(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null,
	): void {
		$this->setParams(
			$length,
			$numeric,
			$lowercase,
			$uppercase,
			$special,
			$redundancy,
			false,
		);
	}

	public function resetParams(): void
	{
		$this->setParams();
	}

	/**
	 * @return mixed[]
	 */
	public function getParams(): array
	{
		return [
			'length' => $this->length,
			'numeric' => ($this->flags & self::NUMERIC) === self::NUMERIC,
			'lowercase' => ($this->flags & self::LOWERCASE) === self::LOWERCASE,
			'uppercase' => ($this->flags & self::UPPERCASE) === self::UPPERCASE,
			'special' => ($this->flags & self::SPECIAL) === self::SPECIAL,
			'redundancy' => $this->redundancy,
		];
	}

	public function isDefaultParameters(): bool
	{
		return self::DEFAULT_PARAMS === $this->getParams();
	}

	#endregion

	#endregion

	#region KeyGen Core functions

	/** Keygen Generator */
	public function keygen(
		?int $length = null,
		?bool $numeric = null,
		?bool $lowercase = null,
		?bool $uppercase = null,
		?bool $special = null,
		?bool $redundancy = null
	): ?string
	{
		$this->clearError();
		$this->updateParams($length, $numeric, $lowercase, $uppercase, $special, $redundancy);

		$charactersSet = $this->getCharactersSet();

		if (empty($this->length)) {
			$this->setError('ERR_EMPTY_LENGTH');
		} elseif ($this->length < 0) {
			$this->setError('ERR_NEGATIVE_LENGTH');
		} elseif (!is_numeric($this->length)) {
			$this->setError('ERR_LENGTH_NOT_NUMERIC');
		} elseif (empty($charactersSet)) {
			$this->setError('ERR_EMPTY_CHARACTERS_SET');
		}

		if ($this->isError()) {
			return null;
		}

		$this->forcedRedundancy = $this->length > strlen($charactersSet) && !$this->redundancy;

		$keygen = '';
		while (strlen($keygen) < $this->length)
		{
			$randomCharacter = substr($charactersSet, mt_rand(0, strlen($charactersSet) - 1), 1);
			if ($this->redundancy || $this->forcedRedundancy || !strstr($keygen, $randomCharacter))
			{
				$keygen .= $randomCharacter;
			}
		}

		return $keygen;
	}

	public function isForcedRedundancy(): bool
	{
		return $this->forcedRedundancy;
	}

	#endregion

	#region Error Management

	/** Set Error */
	private function setError(int|string $id): void
	{
		if (array_search($id, $errorInfos = ['id' => 2, 'code' => 'ERR_EMPTY_LENGTH', 'message' => 'The keygen length parameter cannot be empty.'])) {
			$this->lastError = $errorInfos;
		} elseif (array_search($id, $errorInfos = ['id' => 3, 'code' => 'ERR_NEGATIVE_LENGTH', 'message' => 'The keygen length parameter cannot be negative.'])) {
			$this->lastError = $errorInfos;
		} elseif (array_search($id, $errorInfos = ['id' => 4, 'code' => 'ERR_LENGTH_NULL', 'message' => 'The keygen length parameter cannot be null.'])) {
			$this->lastError = $errorInfos;
		} elseif (array_search($id, $errorInfos = ['id' => 5, 'code' => 'ERR_EMPTY_CHARACTERS_SET', 'message' => 'The character set cannot be empty.'])) {
			$this->lastError = $errorInfos;
		} elseif (array_search($id, $errorInfos = ['id' => 6, 'code' => 'ERR_LENGTH_NOT_NUMERIC', 'message' => 'The keygen length parameter must be numeric.'])) {
			$this->lastError = $errorInfos;
		} else {
			$this->lastError = ['id' => 1, 'code' => 'ERR_UNKNOWN', 'message' => 'An unknown error occurred.'];
		}
	}

	private function clearError(): void
	{
		$this->lastError = null;
	}

	public function isError(): bool
	{
		return $this->lastError !== null;
	}

	/**
	 * Get Error Infos.
	 *
	 * @return mixed[]|null
	 */
	public function getErrorInfos(): ?array
	{
		return $this->lastError ?? null;
	}

	public function getErrorId(): int
	{
		return $this->lastError !== null ? $this->lastError['id'] : 0;
	}

	public function getErrorCode(): ?string
	{
		return $this->lastError !== null ? $this->lastError['code'] : null;
	}

	public function getErrorMessage(): ?string
	{
		return $this->lastError !== null ? $this->lastError['message'] : null;
	}

	#endregion
}
