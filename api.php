<?php
/**
 *  KeyGen Lib API
 *  An open source PHP library for random password generation.
 *
 *  Licensed under the MIT License
 *  Copyright (c) 2017 Matiboux (Mathieu Guérin)
 *
 *  Visit the Github repository: https://github.com/matiboux/KeyGen-Lib
 */

define('API_VERSION', 'v1.2.1');
define('API_REPOSITORY', 'https://github.com/matiboux/KeyGen-Lib');

/** API Content Type */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
/** ------ */

require 'KeyGen-Lib/KeyGen-Lib.php';
use \KeyGenLib\KeyGenStatic as KeyGen;

$_ = array_merge($_GET, $_POST);
$result = [];

/** Params:
 * - "numeric" or "num": boolean
 * - "lowercase" or "low": boolean
 * - "uppercase" or "upp": boolean
 * - "special" or "spe": boolean

/** Keygen Generation */
$numeric = isset($_['numeric']) ? $_['numeric'] : (isset($_['num']) ? $_['num'] : null);
$lowercase = isset($_['lowercase']) ? $_['lowercase'] : (isset($_['low']) ? $_['low'] : null);
$uppercase = isset($_['uppercase']) ? $_['uppercase'] : (isset($_['upp']) ? $_['upp'] : null);
$special = isset($_['special']) ? $_['special'] : (isset($_['spe']) ? $_['spe'] : null);
$length = isset($_['length']) ? $_['length'] : (isset($_['len']) ? $_['len'] : null);
$redundancy = isset($_['redundancy']) ? $_['redundancy'] : (isset($_['red']) ? $_['red'] : null);

$keygen = KeyGen::keygen($length, $numeric, $lowercase, $uppercase, $special, $redundancy);

$result['type'] = 'keygen';
if($result['error'] = KeyGen::isError())
	$result['error-infos'] = KeyGen::getErrorInfos();
$result['response'] = urlencode($keygen);

$result['parameters'] = KeyGen::getParams();
$result['default-parameters'] = KeyGen::isDefaultParameters();
$result['forced-redundancy'] = KeyGen::isForcedRedundancy();

$result['powered-by'] = 'KeyGen Lib';
$result['version'] = API_VERSION;
$result['github'] = API_REPOSITORY;

die(json_encode($result));

/*
if($_['mode'] == 'keygen' OR $_['mode'] == 'cd-key') {
	$numeric = isset($_['numeric']) ? $_['numeric'] : (isset($_['num']) ? $_['num'] : null);
	$lowercase = isset($_['lowercase']) ? $_['lowercase'] : (isset($_['low']) ? $_['low'] : null);
	$uppercase = isset($_['uppercase']) ? $_['uppercase'] : (isset($_['upp']) ? $_['upp'] : null);
	$special = isset($_['special']) ? $_['special'] : (isset($_['spe']) ? $_['spe'] : null);
	$length = isset($_['length']) ? $_['length'] : (isset($_['len']) ? $_['len'] : null);
	$blocks = isset($_['blocks']) ? $_['blocks'] : (isset($_['blocks']) ? $_['blocks'] : null);
	$redundancy = isset($_['redundancy']) ? $_['redundancy'] : (isset($_['red']) ? $_['red'] : null);

	$result['mode'] = $_['mode'];
	$result['return'] = urlencode(KeyGen::keygen($length, $numeric, $lowercase, $uppercase, $special, $redundancy));

	$result['poweredBy'] = 'KeyGen Lib';
	$result['parameters'] = KeyGen::getParams();
	$result['isForcedRedundancy'] = KeyGen::isForcedRedundancy();

	else if($_['mode'] == 'cd-key') {
		if(empty($length)) $length = $result['parameters']['length'];
		if(empty($blocks)) {
			$result['isError'] = true;
			$result['errorInfos'] = 'Number of blocks cannot be null.';
		} else {
			if($blocks > $length) $blocks = $length;

			$newKeyGen = '';
			foreach(str_split($result['return'], round($length / $blocks)) as $eachPart) {
				$newKeyGen .= $eachPart . '-';
			}
			$result['return'] = substr($newKeyGen, 0, -1);
		}
	} else $result['isError'] = false;
} else if($_['mode'] == 'passphrase') {
	$result['isError'] = true;
	$result['errorInfos'] = 'Not yet available.';
} else if($_['mode'] == 'random') {
	$minimum = isset($_['minimum']) ? $_['minimum'] : (isset($_['min']) ? $_['min'] : null);
	$maximum = isset($_['maximum']) ? $_['maximum'] : (isset($_['max']) ? $_['max'] : null);

	if(!is_numeric($minimum)) {
		$result['isError'] = true;
		$result['errorInfos'] = 'Minimum value has to be a number.';
	} else if(!is_numeric($maximum)) {
		$result['isError'] = true;
		$result['errorInfos'] = 'Maximum value has to be a number.';
	} else {
		if($minimum > $maximum) {
			$minimum = [$maximum, $maximum = $minimum][0];
			$result['isInverted'] = true;
		}

		$result['isError'] = false;
		$result['return'] = mt_rand($minimum, $maximum);
	}
} else {
	$result['isError'] = true;
	$result['errorInfos'] = 'Unknown mode selected.';
}

die(json_encode($result, JSON_FORCE_OBJECT));*/
?>