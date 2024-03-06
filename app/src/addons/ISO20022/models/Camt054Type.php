<?php
namespace addons\ISO20022\models;

use yii\helpers\ArrayHelper;

class Camt054Type extends ISO20022Type
{
    const TYPE = 'camt.054';

    public $account;
    public $statementAccountNumber;
    public $companyName;
    public $currency;
    public $periodBegin;
    public $periodEnd;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [array_values($this->attributes()), 'safe'],
        ]);
    }

    public function getType()
    {
        return self::TYPE;
    }

    protected function parseXml($xml = null)
    {
        $this->msgId = (string) $this->_xml->BkToCstmrDbtCdtNtfctn->GrpHdr->MsgId;
        $this->dateCreated = (string) $this->_xml->BkToCstmrDbtCdtNtfctn->GrpHdr->CreDtTm;
        $this->account = $this->statementAccountNumber = (string) $this->_xml->BkToCstmrDbtCdtNtfctn->Ntfctn->Acct->Id->Othr->Id;
        $this->currency = (string) $this->_xml->BkToCstmrDbtCdtNtfctn->Ntfctn->Acct->Ccy;
        $this->periodBegin = (string) $this->_xml->BkToCstmrDbtCdtNtfctn->Ntfctn->FrToDt->FrDtTm;
        $this->periodEnd = (string) $this->_xml->BkToCstmrDbtCdtNtfctn->Ntfctn->FrToDt->ToDtTm;
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }
}