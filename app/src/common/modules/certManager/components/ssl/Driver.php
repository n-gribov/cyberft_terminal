<?php

namespace common\modules\certManager\components\ssl;

use common\modules\certManager\components\ssl\Exception;
use yii\base\BaseObject;

abstract class Driver extends BaseObject implements DriverInterface {
	const MAP_KEY_TYPES		= 1;
	const MAP_CIPHER_ALGO	= 2;
	const MAP_SIGN_ALGO		= 3;
	
	const ERROR_UNKNOWN_KEY_TYPE		= 1;
	const ERROR_UNKNOWN_HASH_ALGORYTHM	= 2;
	const ERROR_PKEY_INVALID				= 3;
	const ERROR_FILE_FORMAT				= 4;
	
	protected $keyTypesMap = [];
	
    protected $cipherAlgoMap = [];

	protected $signAlgoMap = [];
	
	public $keyType;
	public $hashAlgo;


	const KEY_SIZE = 2048;
	const CERT_DAYS = 365;
	
	
	public function __construct($config = array())
	{				
		parent::__construct($config);
		
		$this->keyType = $this->getMapValue(self::MAP_KEY_TYPES, $this->keyType);
		if (false === $this->keyType) {
			throw new Exception("Invalid key type '$this->keyType'");
		}

	}
	
	protected function getMapValue($map, $key) 
	{
		$currentMap = [];
		switch($map)
		{
			case self::MAP_CIPHER_ALGO:
				$currentMap = $this->cipherAlgoMap;
				$errMessage = "Unknown cipher algorythm";
				break;
			case self::MAP_KEY_TYPES:
				$currentMap = $this->keyTypesMap;
				$errMessage = "Unknown key type";
				break;
			case self::MAP_SIGN_ALGO:
				$currentMap = $this->signAlgoMap;
				$errMessage = "Unknown signature algorythm";
				break;
		}
		
		if (array_key_exists($key, $currentMap)) {
			return $currentMap[$key];
		}
		
		return false;
	}

	abstract public function verify($data, $signature, $pubKey);
}