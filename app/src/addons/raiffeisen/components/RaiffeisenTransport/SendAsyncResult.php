<?php

namespace addons\raiffeisen\components\RaiffeisenTransport;

use addons\raiffeisen\models\RaiffeisenRequest;

class SendAsyncResult
{
    /** @var boolean */
    private $isSent;

    /** @var RaiffeisenRequest|null */
    private $importRequest;

    /** @var string|null */
    private $errorMessage;

    public function __construct(bool $isSent, ?RaiffeisenRequest $importRequest, ?string $errorMessage)
    {
        $this->isSent = $isSent;
        $this->importRequest = $importRequest;
        $this->errorMessage = $errorMessage;
    }

    public function isSent(): bool
    {
        return $this->isSent;
    }

    public function getImportRequest(): RaiffeisenRequest
    {
        return $this->importRequest;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
