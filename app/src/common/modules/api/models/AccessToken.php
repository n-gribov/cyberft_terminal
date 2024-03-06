<?php

namespace common\modules\api\models;

class AccessToken
{
    private $secret;
    private $terminalAddress;

    private function __construct(string $secret, ?string $terminalAddress)
    {
        if ($terminalAddress !== null) {
            self::ensureTerminalAddressIsValid($terminalAddress);
        }
        $this->secret = $secret;
        $this->terminalAddress = $terminalAddress;
    }

    public static function fromSecret(string $secret, ?string $terminalAddress): self
    {
        return new self($secret, $terminalAddress);
    }

    public static function fromString(string $value): self
    {
        $decodedValue = base64_decode($value);
        if (preg_match('/^(\*|.*?):(.*)$/s', $decodedValue, $matches)) {
            $terminalAddress = $matches[1] === '*' ? null : $matches[1];
            return new self($matches[2], $terminalAddress);
        }
        throw new \InvalidArgumentException('Invalid access token format');
    }

    public static function generate(?string $terminalAddress, int $secretLength = 256): self
    {
        return new self(random_bytes($secretLength), $terminalAddress);
    }

    public function secret(): string
    {
        return $this->secret;
    }

    public function terminalAddress(): ?string
    {
        return $this->terminalAddress;
    }

    public function __toString()
    {
        $prefix = $this->terminalAddress ?: '*';
        return base64_encode("$prefix:{$this->secret}");
    }

    private static function ensureTerminalAddressIsValid(string $address): void
    {
        if (!preg_match('/[A-Z0-9@]{12}/s', $address)) {
            throw new \InvalidArgumentException("Invalid terminal address: $address");
        }
    }
}
