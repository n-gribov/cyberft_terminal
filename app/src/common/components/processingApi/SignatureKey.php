<?php

namespace common\components\processingApi;

use yii\base\BaseObject;

/**
 * @property string $keyCode
 */
class SignatureKey extends BaseObject
{
    public $body;
    public $password;
    public $terminalAddress;
    public $fingerprint;

    public function getKeyCode(): string
    {
        return "{$this->terminalAddress}-{$this->fingerprint}";
    }
}
