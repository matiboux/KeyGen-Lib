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
 * @method static int getLength()
 * @method static int getFlags()
 * @method static bool getRedundancy()
 * @method static bool isForcedRedundancy()
 * @method static KeyGen setLength(?int $length, bool $useDefaultOnNull = true)
 * @method static KeyGen setFlags(int $flags)
 * @method static KeyGen addFlags(int $flags)
 * @method static KeyGen removeFlags(int $flags)
 * @method static KeyGen setRedundancy(?bool $redundancy, bool $useDefaultOnNull = true)
 * @method static mixed[] getParams()
 * @method static bool isDefaultParameters()
 * @method static void setParams(?int $length = null, ?bool $numeric = null, ?bool $lowercase = null, ?bool $uppercase = null, ?bool $special = null, ?bool $redundancy = null)
 * @method static void updateParams(?int $length = null, ?bool $numeric = null, ?bool $lowercase = null, ?bool $uppercase = null, ?bool $special = null, ?bool $redundancy = null)
 * @method static void resetParams()
 * @method static bool isError()
 * @method static ?array getErrorInfos()
 * @method static int getErrorId()
 * @method static ?string getErrorCode()
 * @method static ?string getErrorMessage()
 * @method static ?string keygen(?int $length = null, ?bool $numeric = null, ?bool $lowercase = null, ?bool $uppercase = null, ?bool $special = null, ?bool $redundancy = null)
 *
 * @author Matiboux <matiboux@gmail.com>
 */
class KeyGenStatic
{
	#region Static context use

	private static KeyGen $instance;

	public static function getStaticInstance(): KeyGen
	{
		if (!isset(self::$instance))
		{
			self::$instance = new KeyGen();
		}

		return self::$instance;
	}

	/**
	 * @param string $name
	 * @param mixed[] $arguments
	 * @return mixed
	 */
    public static function __callStatic(string $name, array $arguments): mixed
    {
		$instance = self::getStaticInstance();
		return $instance->$name(...$arguments);
    }

	#endregion
}
