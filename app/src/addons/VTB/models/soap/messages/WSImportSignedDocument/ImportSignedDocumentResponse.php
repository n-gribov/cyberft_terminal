<?php

namespace addons\VTB\models\soap\messages\WSImportSignedDocument;

use addons\VTB\models\soap\messages\BaseMessage;

class ImportSignedDocumentResponse extends BaseMessage
{
    protected $BSError;
    protected $BSErrorCode;
    protected $RecordID;

    public function setBSError($value)
    {
        $this->setProperty('BSError', $value);
        return $this;
    }

    public function getBSError()
    {
        $this->checkPropertyExists('BSError');
        return $this->BSError;
    }

    public function setBSErrorCode($value)
    {
        $this->setProperty('BSErrorCode', $value);
        return $this;
    }

    public function getBSErrorCode()
    {
        $this->checkPropertyExists('BSErrorCode');
        return $this->BSErrorCode;
    }

    public function setRecordID($value)
    {
        $this->setProperty('RecordID', $value);
        return $this;
    }

    public function getRecordID()
    {
        $this->checkPropertyExists('RecordID');
        return $this->RecordID;
    }

}
