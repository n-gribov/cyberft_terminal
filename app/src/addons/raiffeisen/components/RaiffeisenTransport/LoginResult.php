<?php

namespace addons\raiffeisen\components\RaiffeisenTransport;

class LoginResult
{
    /** @var boolean */
    private $isLoggedIn;

    /** @var string|null */
    private $sessionId;

    public function __construct(bool $isLoggedIn, $sessionId)
    {
        $this->isLoggedIn = $isLoggedIn;
        $this->sessionId = $sessionId;
    }

    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }
}
