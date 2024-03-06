<?php
namespace addons\ISO20022\models;

use yii\helpers\ArrayHelper;

class Pain002Type extends ISO20022Type
{
    const TYPE = 'pain.002';

    public $msgId;
    public $originalMsgId;
    //
    public $statusCodeGrp;
    public $statusCodePmt;
    //
    public $errorCode;
    //
    public $errorDescriptionGrp;
    public $errorDescriptionPmt;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [array_values($this->attributes()), 'safe'],
            ]);
    }

    public function getType()
    {
        return self::TYPE;
    }

    protected function parseXml($xml = null)
    {
        $this->msgId = (string) $this->_xml->CstmrPmtStsRpt->GrpHdr->MsgId;
        $this->dateCreated = (string) $this->_xml->CstmrPmtStsRpt->GrpHdr->CreDtTm;
        $this->originalMsgId = (string) $this->_xml->CstmrPmtStsRpt->OrgnlGrpInfAndSts->OrgnlMsgId;

        if (isset($this->_xml->CstmrPmtStsRpt->OrgnlPmtInfAndSts->TxInfAndSts->TxSts)) {
            $this->statusCodePmt = (string) $this->_xml->CstmrPmtStsRpt->OrgnlPmtInfAndSts->TxInfAndSts->TxSts;
        }

        if (isset($this->_xml->CstmrPmtStsRpt->OrgnlGrpInfAndSts->GrpSts)) {
            $this->statusCodeGrp = (string) $this->_xml->CstmrPmtStsRpt->OrgnlGrpInfAndSts->GrpSts;
        }

        if (isset($this->_xml->CstmrPmtStsRpt->OrgnlGrpInfAndSts->StsRsnInf->AddtlInf)) {
            $this->errorDescriptionGrp = static::joinTagsContent(
                $this->_xml->CstmrPmtStsRpt->OrgnlGrpInfAndSts->StsRsnInf,
                "./*[local-name()='AddtlInf']"
            );
        }
        
        if (isset($this->_xml->CstmrPmtStsRpt->OrgnlPmtInfAndSts->TxInfAndSts->StsRsnInf->AddtlInf)) {
            $this->errorDescriptionPmt = static::joinTagsContent(
                $this->_xml->CstmrPmtStsRpt->OrgnlPmtInfAndSts->TxInfAndSts->StsRsnInf,
                "./*[local-name()='AddtlInf']"
            );
        }

        if (isset($this->_xml->CstmrPmtStsRpt->OrgnlGrpInfAndSts->StsRsnInf->Rsn->Cd)) {
            $this->errorCode = (string) $this->_xml->CstmrPmtStsRpt->OrgnlGrpInfAndSts->StsRsnInf->Rsn->Cd;
        }
    }

    public function getSearchFields()
    {
        return false;
    }

    /**
     * Получение кода статуса по типу
     * документа, к которому он относится
     * @param $originType
     * @return mixed
     */
    public function getStatusCodeByType($originType)
    {
        if ($originType == Auth026Type::TYPE) {
            return $this->statusCodeGrp ?: $this->statusCodePmt;
        } elseif ($originType == Pain001Type::TYPE) {
            return $this->statusCodeGrp;
        }
    }

    /**
     * Получение описания ошибки по типу
     * документа, к которому он относится
     * @param $originType
     * @return mixed
     */
    public function getErrorDescriptionByType($originType)
    {
        if ($originType == Auth026Type::TYPE) {
            return $this->errorDescriptionGrp ?: $this->errorDescriptionPmt;
        } else if ($originType == Pain001Type::TYPE) {
            return $this->errorDescriptionGrp;
        }
    }

}