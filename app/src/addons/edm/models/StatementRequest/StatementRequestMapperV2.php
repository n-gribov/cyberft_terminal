<?php

namespace addons\edm\models\StatementRequest;

use DOMDocument;
use SimpleXMLElement;
use Yii;

class StatementRequestMapperV2
{
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/edm.02';
    const XSD_SCHEME = '@addons/edm/resources/xsd/CyberFT_EDM_v2.7.xsd';
    public $message;

    public function validateXSD($xml)
    {
        $this->message = null;
		libxml_use_internal_errors(true);

        /** @todo надо все перевести на одну библиотеку вместо этих плясок */
        $domElement = dom_import_simplexml($xml);
        $dom = new DOMDocument();
        $dom->appendChild($dom->importNode($domElement, true));

		if ($dom->schemaValidate(Yii::getAlias(self::XSD_SCHEME))) {

			return true; // Успешная валидация по XSD-схеме
		}

		$errors = libxml_get_errors();
		$messages = [];
		foreach ($errors as $error) {
			$messages[] = "[{$error->level}] {$error->message}";
		}

		$this->message = join(PHP_EOL, $messages);

		return false; // Ошибка валидации
	}

    public function buildXml(StatementRequestType $model)
    {
        $xml = new SimpleXMLElement('<StatementRequest xmlns="' . self::DEFAULT_NS_URI . '"></StatementRequest>');

        $xml->StatementAccount->AccountNumber = $model->accountNumber;
        $xml->StatementAccount->BIK           = $model->BIK;
        $xml->StatementPeriod->StartDate      = $model->startDate;
        $xml->StatementPeriod->EndDate        = $model->endDate;

        return $xml;
    }

    public function parseXml($xmlDom, StatementRequestType $model)
    {
        $model->accountNumber = (string) $xmlDom->StatementAccount->AccountNumber;
        $model->BIK           = (string) $xmlDom->StatementAccount->BIK;
        $model->startDate     = (string) $xmlDom->StatementPeriod->StartDate;
        $model->endDate       = (string) $xmlDom->StatementPeriod->EndDate;
    }

}