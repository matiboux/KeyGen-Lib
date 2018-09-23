<?php
/*\
|*|  ----------------------------
|*|  --- [  KeyGen Library  ] ---
|*|  --- [  Version 1.0.0   ] ---
|*|  ----------------------------
|*|  
|*|  KeyGen Library is an open source random password generator PHP library.
|*|  This project is directly related to matiboux's KeyGen project.
|*|  
|*|  KeyGen Library Github repository: https://github.com/matiboux/KeyGen-Lib
|*|  
|*|  Creator & Developer: Matiboux (Mathieu Guérin)
|*|    → Github: https://github.com/matiboux
|*|    → Email: matiboux@gmail.com
|*|  
|*|  For more info, please read the README.md file.
|*|  You can find it in the project repository (Github link above).
|*|  
|*|  --- --- ---
|*|  
|*|  MIT License
|*|  Copyright (C) 2017 Matiboux (Mathieu Guérin)
|*|  You'll find a copy of the MIT license in the LICENSE file.
|*|  
|*|  --- --- ---
|*|  
|*|  Releases date:
|*|  - VERSION 1:
|*|    * v1.0.0: April 15, 2017
|*|       1.0.1: [WIP]
\*/

namespace KeyGenLib {

class KeyGen {
	
	/** Character Sets */
	const NUMERIC = '1234567890';
	const LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
	const UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const SPECIAL = '!#$%&\()+-;?@[]^_{|}';
	
	/** Default Values */
	private static $defaultLength = 12;
	private static $defaultNumeric = true;
	private static $defaultLowercase = true;
	private static $defaultUppercase = true;
	private static $defaultSpecial = false;
	private static $defaultRedundancy = true;
	
	/** Parameters */
	private static $length = null;
	private static $numeric = null;
	private static $lowercase = null;
	private static $uppercase = null;
	private static $special = null;
	private static $redundancy = null;
	
	/** Indicators */
	private static $defaultParameters = true;
	private static $forcedRedundancy = false;
	
	/** Error */
	private static $lastError = null;
	
	/** ----------------------- */
	/**  Parameters Management  */
	/** ----------------------- */
	
	public static function setParams($length = null, $numeric = null, $lowercase = null, $uppercase = null, $special = null, $redundancy = null) {
		self::$length = isset($length) ? $length : self::$defaultLength;
		self::$numeric = isset($numeric) ? $numeric : self::$defaultNumeric;
		self::$lowercase = isset($lowercase) ? $lowercase : self::$defaultLowercase;
		self::$uppercase = isset($uppercase) ? $uppercase : self::$defaultUppercase;
		self::$special = isset($special) ? $special : self::$defaultSpecial;
		self::$redundancy = isset($redundancy) ? $redundancy : self::$defaultRedundancy;
		
		if(self::getParams() == self::getDefaultParams()) self::$defaultParameters = true;
		else self::$defaultParameters = false;
	}
	
	public static function updateParams($length = null, $numeric = null, $lowercase = null, $uppercase = null, $special = null, $redundancy = null) {
		if(isset($length)) self::$length = $length;
		else if(!isset(self::$length)) self::$length = self::$defaultLength;
		if(isset($numeric)) self::$numeric = $numeric;
		else if(!isset(self::$numeric)) self::$numeric = self::$defaultNumeric;
		if(isset($lowercase)) self::$lowercase = $lowercase;
		else if(!isset(self::$lowercase)) self::$lowercase = self::$defaultLowercase;
		if(isset($uppercase)) self::$uppercase = $uppercase;
		else if(!isset(self::$uppercase)) self::$uppercase = self::$defaultUppercase;
		if(isset($special)) self::$special = $special;
		else if(!isset(self::$special)) self::$special = self::$defaultSpecial;
		if(isset($redundancy)) self::$redundancy = $redundancy;
		else if(!isset(self::$redundancy)) self::$redundancy = self::$defaultRedundancy;
		
		if(self::getParams() == self::getDefaultParams()) self::$defaultParameters = true;
		else self::$defaultParameters = false;
	}
	
	public static function resetParams() { return self::setParams(); }
	
	public static function getDefaultParams() {
		return array('length' => self::$defaultLength,
			'numeric' => self::$defaultNumeric,
			'lowercase' => self::$defaultLowercase,
			'uppercase' => self::$defaultUppercase,
			'special' => self::$defaultSpecial,
			'redundancy' => self::$defaultRedundancy);
	}
	
	public static function getParams() {
		return array('length' => isset(self::$length) ? self::$length : self::$defaultLength,
		'numeric' => isset(self::$numeric) ? self::$numeric : self::$defaultNumeric,
		'lowercase' => isset(self::$lowercase) ? self::$lowercase : self::$defaultLowercase,
		'uppercase' => isset(self::$uppercase) ? self::$uppercase : self::$defaultUppercase,
		'special' => isset(self::$special) ? self::$special : self::$defaultSpecial,
		'redundancy' => isset(self::$redundancy) ? self::$redundancy : self::$defaultRedundancy);
	}
	
	public static function isDefaultParameters() {
		return self::$defaultParameters;
	}
	
	/** ---------------------- */
	/**  KeyGen Core functions */
	/** ---------------------- */
	
	/** Keygen Generator */
	public static function keygen($length = null, $numeric = null, $lowercase = null, $uppercase = null, $special = null, $redundancy = null) {
		self::clearError();
		self::updateParams($length, $numeric, $lowercase, $uppercase, $special, $redundancy);
		
		$charactersSet = '';
		if(self::$numeric) $charactersSet .= self::NUMERIC;
		if(self::$lowercase) $charactersSet .= self::LOWERCASE;
		if(self::$uppercase) $charactersSet .= self::UPPERCASE;
		if(self::$special) $charactersSet .= self::SPECIAL;
		
		if(empty(self::$length)) self::setError('ERR_EMPTY_LENGTH');
		else if(self::$length < 0) self::setError('ERR_NEGATIVE_LENGTH');
		else if(!is_numeric(self::$length)) self::setError('ERR_LENGTH_NOT_NUMERIC');
		else if(!self::$length) self::setError('ERR_LENGTH_NULL');
		else if(empty($charactersSet)) self::setError('ERR_EMPTY_CHARACTERS_SET');
		
		if(!self::isError()) {
			if(self::$length > strlen($charactersSet) AND !self::$redundancy) self::$forcedRedundancy = true;
			else self::$forcedRedundancy = false;
			
			$keygen = '';
			while(strlen($keygen) < self::$length) {
				$randomCharacter = substr($charactersSet, mt_rand(0, strlen($charactersSet) - 1), 1);
				if(self::$redundancy OR self::$forcedRedundancy OR !strstr($keygen, $randomCharacter)) $keygen .= $randomCharacter;
			}
			
			return $keygen;
		} else return false;
	}
	
	public static function isForcedRedundancy() { return self::$forcedRedundancy; }
	
	/** ------------------ */
	/**  Error Management  */
	/** ------------------ */
	
	/** Set / Clear Error */
	private static function setError($id) {
		if(array_search($id, $errorInfos = array('id' => 2, 'code' => 'ERR_EMPTY_LENGTH', 'message' => 'The keygen length parameter cannot be empty.'))) self::$lastError = $errorInfos;
		else if(array_search($id, $errorInfos = array('id' => 3, 'code' => 'ERR_NEGATIVE_LENGTH', 'message' => 'The keygen length parameter cannot be negative.'))) self::$lastError = $errorInfos;
		else if(array_search($id, $errorInfos = array('id' => 4, 'code' => 'ERR_LENGTH_NULL', 'message' => 'The keygen length parameter cannot be null.'))) self::$lastError = $errorInfos;
		else if(array_search($id, $errorInfos = array('id' => 5, 'code' => 'ERR_EMPTY_CHARACTERS_SET', 'message' => 'The character set cannot be empty.'))) self::$lastError = $errorInfos;
		else if(array_search($id, $errorInfos = array('id' => 6, 'code' => 'ERR_LENGTH_NOT_NUMERIC', 'message' => 'The keygen length parameter must be numeric.'))) self::$lastError = $errorInfos;
		else self::$lastError = array('id' => 1, 'code' => 'ERR_UNKNOWN', 'message' => 'An unknown error occurred.');
	}
	private static function clearError() { self::$lastError = null; }
	public static function isError() { return self::$lastError !== null; }
	
	/** Get Error Infos */
	public static function getErrorInfos() { return self::isError() ? self::$lastError : false; }
	public static function getErrorId() { return self::isError() ? self::$lastError['id'] : 0; }
	public static function getErrorCode() { return self::isError() ? self::$lastError['code'] : false; }
	public static function getErrorMessage() { return self::isError() ? self::$lastError['message'] : false; }

}

}
?>