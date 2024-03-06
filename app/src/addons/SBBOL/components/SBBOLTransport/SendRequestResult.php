<?php

namespace addons\SBBOL\components\SBBOLTransport;

class SendRequestResult
{
    /** @var boolean */
    private $isSent;

    /** @var string|null */
    private $requestId;

    /** @var string|null */
    private $errorMessage;

    /**
     * SendAsyncResult constructor.
     * @param bool $isSent
     * @param string|null $requestId
     * @param string|null $errorMessage
     */
    public function __construct(bool $isSent, $requestId, $errorMessage)
    {
        $this->isSent = $isSent;
        $this->requestId = $requestId;
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
     * @return string|null
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

}
