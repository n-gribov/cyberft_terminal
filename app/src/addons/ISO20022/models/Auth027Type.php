<?php
namespace addons\ISO20022\models;

use yii\helpers\ArrayHelper;

class Auth027Type extends ISO20022Type
{
    const TYPE = 'auth.027';

    public $msgId;
    public $originalMsgId;
    public $statusCode;
    public $errorDescription;

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
        $this->msgId = (string) $this->_xml->CcyCtrlStsAdvc->GrpHdr->MsgId;
        $this->dateCreated = (string) $this->_xml->CcyCtrlStsAdvc->GrpHdr->CreDtTm;
        $this->originalMsgId = (string) $this->_xml->CcyCtrlStsAdvc->GrpSts->OrgnlRefs->OrgnlMsgId;

        if (isset($this->_xml->CcyCtrlStsAdvc->GrpSts->Sts)) {
            $this->statusCode = (string) $this->_xml->CcyCtrlStsAdvc->GrpSts->Sts;
        }

        $statusDescriptionElement = $this->_xml->CcyCtrlStsAdvc->GrpSts->StsRsn->AddtlInf
            ?? $this->_xml->CcyCtrlStsAdvc->GrpSts->Rsn->AddtlInf // Gvozd for Alfabank
            ?? null;

        $this->errorDescription = $statusDescriptionElement !== null
            ? (string)$statusDescriptionElement
            : null;
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