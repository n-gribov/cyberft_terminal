<?php

namespace addons\swiftfin\models\documents\mt;

use addons\swiftfin\models\documents\mt\mtUniversal\Collection;
use yii\helpers\ArrayHelper;

/**
 * Class MtUniversal950Document
 * @package addons\swiftfin\models\documents\mt
 */
class MtUniversal950Document extends MtUniversalDocument 
{
	/**
	 * @var array Массив связанных референсов документа
	 */
	protected $_operationReferences = null;
	
	/**
	 * Функция возвращает массив связанных референсов документа
	 * @return array
	 */
	public function getOperationReferences()
	{
		if (is_null($this->_operationReferences)) {
			$this->_operationReferences = $this->operationReferences;
		}
		
		return $this->_operationReferences;
	}
	
	public function findReferenced()
	{
		$referencedArray = [];

		/** @var Collection $references */
		$references = $this->getOperationReferences();
		$count = $references->count();

        $matchesKeys = [
            'valueDate' => null,
            'entryDate' => null,
            'debitCreditMark' => null,
            'fundsCode' => null,
            'amount' => null,
            'transactionType' => null,
            'identificationCode' => null,
            'referenceForAccOwner' => null,
            'other' => null,
        ];

		$matches = [];

		for ($cnt = 0; $cnt < $count - 1; $cnt++) {
//			preg_match('/(?P<valueDate>\d{6})(?P<entryDate>\d{4})?(?P<debitCreditMark>C|D|RC|RD)(?P<fundsCode>[A-Z]{1})?(?P<amount>[\d,]{1,15})(?P<transactionType>S|N|F)(?P<identificationCode>[A-Z\d]{3})(?P<referenceForAccOwner>[A-Z\-\/\d]{1,16})\/\/(?P<other>\/\/.*)?/s',
			preg_match('/(?P<valueDate>\d{6})(?P<entryDate>\d{4})?(?P<debitCreditMark>C|D|RC|RD)(?P<fundsCode>[A-Z]{1})?(?P<amount>[\d,]{1,15})(?P<transactionType>S|N|F)(?P<identificationCode>[A-Z\d]{3})(?P<referenceForAccOwner>[A-Z\-\/\.\d]{1,16})(?P<other>\/\/.*)?/s',
					$references->getNode($cnt)->getNode('61')->getValue(),
					$matches
			);

            $matches = ArrayHelper::merge($matchesKeys, $matches);

			$referencedArray[] = [
				'valueDate' => $matches['valueDate'],
				'entryDate' => $matches['entryDate'],
				'debitCreditMark' => $matches['debitCreditMark'],
				'fundsCode' => $matches['fundsCode'],
				'sum' => str_replace(',', '.', $matches['amount']),
				'transactionType' => $matches['transactionType'],
				'docType' => $matches['identificationCode'],
				'operationReference' => $matches['referenceForAccOwner'],
				'other' => $matches['other'],
			];
		}
		
		return $referencedArray;
	}
}