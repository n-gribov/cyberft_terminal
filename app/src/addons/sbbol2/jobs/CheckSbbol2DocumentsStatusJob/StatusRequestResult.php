<?php

namespace addons\sbbol2\jobs\CheckSbbol2DocumentsStatusJob;

class StatusRequestResult
{
    private $hasError;
    private $canRetry;

    public function __construct($hasError, $canRetry, $documentStatus = null)
    {
        $this->hasError = $hasError;
        $this->canRetry = $canRetry;
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


}
