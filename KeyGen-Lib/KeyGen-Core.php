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
|*|  Copyright (C) 2017 Matiboux (Mathieu Guérin)
|*|  You'll find a copy of the MIT license in the LICENSE file.
\*/

namespace KeyGen {

class KeyGenLib {
	
	/** Character Sets */
	const NUMERIC = '1234567890';
	const LOWERCASE = 'abcdefghijklmnopqrstuvwxyz';
	const UPPERCASE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const SPECIAL = '!#$%&\()+-;?@[]^_{|}';
	
	/** Parameters */
	private static $defaultLength = 12;
	private static $defaultNumeric = true;
	private static $defaultLowercase = true;
	private static $defaultUppercase = true;
	private static $defaultSpecial = false;
	private static $defaultRedundancy = false;
	
	private static $length = null;
	private static $numeric = null;
	private static $lowercase = null;
	private static $uppercase = null;
	private static $special = null;
	private static $redundancy = null;
	
	private static $forcedRedundancy = false;
	
	/** Errors */
	private static $lastError = null;
	
	/** ------------------ */
	/**  Params functions  */
	/** ------------------ */
	
	public static function setParams($length = null, $numeric = null, $lowercase = null, $uppercase = null, $special = null, $redundancy = null) {
		self::$length = isset($length) ? $length : self::$defaultLength;
		self::$numeric = isset($numeric) ? $numeric : self::$defaultNumeric;
		self::$lowercase = isset($lowercase) ? $lowercase : self::$defaultLowercase;
		self::$uppercase = isset($uppercase) ? $uppercase : self::$defaultUppercase;
		self::$special = isset($special) ? $special : self::$defaultSpecial;
		self::$redundancy = isset($redundancy) ? $redundancy : self::$defaultRedundancy;
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
	}
	public static function resetParams() { self::setParams(); }
	
	public static function getDefaultParams() { return array('length' => self::$defaultLength, 'numeric' => self::$defaultNumeric, 'lowercase' => self::$defaultLowercase, 'uppercase' => self::$defaultUppercase, 'special' => self::$defaultSpecial, 'redundancy' => self::$defaultRedundancy); }
	public static function getParams() { return array('length' => isset(self::$length) ? self::$length : self::$defaultLength, 'numeric' => isset(self::$numeric) ? self::$numeric : self::$defaultNumeric, 'lowercase' => isset(self::$lowercase) ? self::$lowercase : self::$defaultLowercase, 'uppercase' => isset(self::$uppercase) ? self::$uppercase : self::$defaultUppercase, 'special' => isset(self::$special) ? self::$special : self::$defaultSpecial, 'redundancy' => isset(self::$redundancy) ? self::$redundancy : self::$defaultRedundancy); }
	
	/** ------------------ */
	/**  KeyGen functions  */
	/** ------------------ */
	
	/** KeyGen function */
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
		if($id == 'ERR_EMPTY_LENGTH' OR $id == 2) self::$lastError = array('id' => 2, 'code' => 'ERR_EMPTY_LENGTH', 'message' => 'The keygen length parameter cannot be empty.');
		if($id == 'ERR_NEGATIVE_LENGTH' OR $id == 3) self::$lastError = array('id' => 3, 'code' => 'ERR_NEGATIVE_LENGTH', 'message' => 'The keygen length parameter cannot be negative.');
		if($id == 'ERR_LENGTH_NULL' OR $id == 4) self::$lastError = array('id' => 4, 'code' => 'ERR_LENGTH_NULL', 'message' => 'The keygen length parameter cannot be null.');
		if($id == 'ERR_EMPTY_CHARACTERS_SET' OR $id == 5) self::$lastError = array('id' => 5, 'code' => 'ERR_EMPTY_CHARACTERS_SET', 'message' => 'The character set cannot be empty.');
		else self::$lastError = array('id' => 1, 'code' => 'ERR_UNKNOWN', 'message' => 'An unknown error occurred.');
	}
	private static function clearError() { self::$lastError = null; }
	public static function isError() { return !empty(self::$lastError); }
	
	/** Get Error Infos */
	public static function getErrorInfos() { return self::isError() ? self::$lastError : false; }
	public static function getErrorId() { return self::isError() ? self::$lastError['id'] : 0; }
	public static function getErrorCode() { return self::isError() ? self::$lastError['code'] : false; }
	public static function getErrorMessage() { return self::isError() ? self::$lastError['message'] : false; }

}

}
?>