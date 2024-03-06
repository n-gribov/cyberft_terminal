<?php

namespace common\components\processingApi\models;

class TerminalStompCredentials extends JsonSerializableObject
{
    public $terminal;
    public $passwordHash;
    public $passwordSalt;

    protected function mapping(): array
    {
        return [
            'terminal'     => 'terminal',
            'passwordHash' => 'passwordHash',
            'passwordSalt' => 'passwordSalt',
        ];
    }
}
