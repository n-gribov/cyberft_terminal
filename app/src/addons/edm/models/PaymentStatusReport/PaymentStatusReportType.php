<?php

namespace addons\edm\models\PaymentStatusReport;

use common\base\BaseType;
use common\helpers\StringHelper;
use DOMDocument;
use SimpleXMLElement;
use Yii;
use yii\helpers\ArrayHelper;


class PaymentStatusReportType extends BaseType
{
    const TYPE           = 'PaymentStatusReport';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/swiftfin.02';
    const XSD_SCHEME = '@addons/edm/resources/xsd/CyberFT_EDM_latest.xsd';
    public $message;

    public $refDocId;
    public $refDocDate;
    public $refSenderId;
    public $registerId;
    public $registerDate;
    public $statusCode;
    public $statusDescription;
    public $statusComment;
    public $paymentCount;
    public $paymentSum;
    public $transactionStatus;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [
                    array_values($this->attributes()), 'safe'
                ],
            ]
        );
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function validateXSD()
    {
        $this->message = null;
		libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadXML((string)$this);

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

    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($clearErrors) {
            $this->clearErrors();
        }

        return parent::validate($attributeNames, false);
    }

    public function loadFromString($string, $isFile = false, $encoding = null)
    {
        $xml = simplexml_load_string($string, 'SimpleXMLElement', LIBXML_PARSEHUGE);
        $this->parseXml($xml);

        return $this;
    }

    public function buildXml()
    {
        $xml = new SimpleXMLElement('<PaymentStatusReport xmlns="' . self::DEFAULT_NS_URI . '"></PaymentStatusReport>');
        $xml->RefDoc->RefDocId = $this->refDocId;
        $xml->RefDoc->RefDocDate = $this->refDocDate;
        $xml->RefDoc->RefSenderId = $this->refSenderId;
        $xml->GroupStatus->RegisterId = $this->registerId;
        $xml->GroupStatus->RegisterDate = $this->registerDate;
        $xml->GroupStatus->StatusCode = $this->statusCode; //'ACTC'
        $xml->GroupStatus->StatusDescription = $this->statusDescription; //'Реестр платежей принят в обработку'
        $xml->GroupStatus->StatusReason->Reason = $this->statusComment; //Ошибка разбора полученного конверта.TO_handlexml
        $xml->GroupStatus->PaymentCount = $this->paymentCount;
        $xml->GroupStatus->PaymentSum = $this->paymentSum;

        if (!empty($this->transactionStatus)) {
            foreach ($this->transactionStatus as $docNumber => $transactionStatus) {
                $transactionStatusElement = $xml->GroupStatus->addChild('TransactionStatus');
                $transactionStatusElement->StatusCode = $transactionStatus['statusCode'];
                $transactionStatusElement->StatusDescription = $transactionStatus['statusDescription'];
                $transactionStatusElement->StatusReason->Reason = $transactionStatus['statusReason'];
                $transactionStatusElement->Details->RefTrnInfo->DocNumber = $docNumber;
            }
        }

        return $xml;
    }

    public function parseXml($xml)
    {
        $this->refDocId = (string) $xml->RefDoc->RefDocId;
        $this->refDocDate = (string) $xml->RefDoc->RefDocDate;
        $this->refSenderId = (string) $xml->RefDoc->RefSenderId;
        $this->registerId = (string) $xml->GroupStatus->RegisterId;
        $this->registerDate = (string) $xml->GroupStatus->RegisterDate;
        $this->statusCode = (string) $xml->GroupStatus->StatusCode;
        $this->statusDescription = (string) $xml->GroupStatus->StatusDescription;
        $this->statusComment = (string) $xml->GroupStatus->StatusReason->Reason;
        $this->paymentCount = (string) $xml->GroupStatus->PaymentCount;
        $this->paymentSum = (string) $xml->GroupStatus->PaymentSum;

        // Получение информации по статусам конкретных платежных поручений
        foreach ($xml->GroupStatus->TransactionStatus as $node) {
            $docNumber = (string) $node->Details->RefTrnInfo->DocNumber;
            $statusCode = (string) $node->StatusCode;
            $statusDescription = (string) $node->StatusDescription;
            $statusReason = (string) $node->StatusReason->Reason;

            $this->transactionStatus[$docNumber] = [
                'statusCode' => $statusCode,
                'statusDescription' => $statusDescription,
                'statusReason' => $statusReason,
            ];
        }
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    public function getModelDataAsString()
    {
        // Сформировать XML
        $xml = $this->buildXml();
        $body = StringHelper::fixBOM($xml->asXML());

        return trim(preg_replace('/^.*?\<\?xml.*\?>/uim', '', $body));
    }

    public function attributeLabels()
    {
        return [
            'startDate' => Yii::t('edm', 'Start date'),
            'endDate'   => Yii::t('edm', 'End date'),
        ];
    }

}