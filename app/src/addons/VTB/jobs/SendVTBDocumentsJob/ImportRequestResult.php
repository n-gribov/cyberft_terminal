<?php


namespace addons\VTB\jobs\SendVTBDocumentsJob;


class ImportRequestResult
{
    private $hasError;
    private $canRetry;

    public function __construct($hasError, $canRetry)
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
