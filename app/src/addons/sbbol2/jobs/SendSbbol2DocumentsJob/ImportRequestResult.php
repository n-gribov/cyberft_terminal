<?php

namespace addons\sbbol2\jobs\SendSbbol2DocumentsJob;

class ImportRequestResult
{
    const HAS_ERROR = true;
    const CAN_RETRY = true;

    private $hasError;
    private $canRetry;
    private $externalId;

    public function __construct($hasError, $canRetry, $externalId)
    {
        $this->hasError = $hasError;
        $this->canRetry = $canRetry;
        $this->externalId = $externalId;
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

    /*
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

}
