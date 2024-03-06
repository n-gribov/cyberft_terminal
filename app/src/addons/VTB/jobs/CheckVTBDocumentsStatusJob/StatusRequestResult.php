<?php

namespace addons\VTB\jobs\CheckVTBDocumentsStatusJob;


use addons\VTB\models\VTBDocumentStatus;

class StatusRequestResult
{
    private $hasError;
    private $canRetry;
    private $documentStatus;
    private $documentInfoXml;

    public function __construct($hasError, $canRetry, $documentStatus = null, $documentInfoXml = null)
    {
        $this->hasError = $hasError;
        $this->canRetry = $canRetry;
        $this->documentStatus = $documentStatus;
        $this->documentInfoXml = $documentInfoXml;
    }

    /**
     * @return boolean
     */
    public function hasError()
    {
        return $this->hasError;
    }

    /**
     * @return boolean
     */
    public function canRetry()
    {
        return $this->canRetry;
    }

    /**
     * @return VTBDocumentStatus|null
     */
    public function getDocumentStatus()
    {
        return $this->documentStatus;
    }

    /**
     * @return string|null
     */
    public function getDocumentInfoXml()
    {
        return $this->documentInfoXml;
    }
}
