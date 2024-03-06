<?php

namespace addons\SBBOL\components\SBBOLTransport;

use addons\SBBOL\models\SBBOLRequest;

class SendAsyncResult
{
    /** @var boolean */
    private $isSent;

    /** @var SBBOLRequest */
    private $importRequest;

    /** @var string|null */
    private $errorMessage;

    /**
     * SendAsyncResult constructor.
     * @param bool $isSent
     * @param SBBOLRequest|null $importRequest
     * @param string|null $errorMessage
     */
    public function __construct(bool $isSent, $importRequest, $errorMessage)
    {
        $this->isSent = $isSent;
        $this->importRequest = $importRequest;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->isSent;
    }

    /**
     * @return SBBOLRequest
     */
    public function getImportRequest(): SBBOLRequest
    {
        return $this->importRequest;
    }

    /**
     * @return null|string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
