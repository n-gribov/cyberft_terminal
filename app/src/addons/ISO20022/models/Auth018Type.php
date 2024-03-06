<?php
namespace addons\ISO20022\models;

use addons\ISO20022\models\traits\WithAttachments;
use yii\helpers\ArrayHelper;

class Auth018Type extends ISO20022Type
{
    const TYPE = 'auth.018';

    use WithAttachments;

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
        $this->msgId = (string) $this->_xml->CtrctRegnReq->GrpHdr->MsgId;
        $this->dateCreated = (string) $this->_xml->CtrctRegnReq->GrpHdr->CreDtTm;
        $this->mmbId = (string) $this->_xml->CtrctRegnReq->CtrctRegn->RegnAgt->FinInstnId->ClrSysMmbId->MmbId;
    }

    public function getSearchFields()
    {
        return false;
    }
}