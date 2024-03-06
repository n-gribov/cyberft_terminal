<?php

namespace addons\SBBOL\components\SBBOLTransport;

use addons\SBBOL\models\AuthReturnCode;

class LoginResult
{
    /** @var boolean */
    private $isLoggedIn;

    /** @var AuthReturnCode */
    private $returnCode;

    /** @var string|null */
    private $sessionId;

    public function __construct(bool $isLoggedIn, AuthReturnCode $returnCode, $sessionId)
    {
        $this->isLoggedIn = $isLoggedIn;
        $this->returnCode = $returnCode;
        $this->sessionId = $sessionId;
    }

    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

    public function getReturnCode(): AuthReturnCode
    {
        return $this->returnCode;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function isPasswordChangeRequired(): bool
    {
        return $this->returnCode->getId() === AuthReturnCode::MUST_CHANGE_PASSWORD;
    }
}
