<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;

class EncryptAttributesBehavior extends Behavior
{
    public $attributes = [];
    public $encryptionKey;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'decryptAttributes',
            ActiveRecord::EVENT_BEFORE_INSERT => 'encryptAttributes',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encryptAttributes',
            ActiveRecord::EVENT_AFTER_INSERT => 'decryptAttributes',
            ActiveRecord::EVENT_AFTER_UPDATE => 'decryptAttributes',
        ];
    }

    public function encryptAttributes(Event $event)
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->$attribute = $this->encrypt($this->owner->$attribute);
        }
    }

    public function decryptAttributes(Event $event)
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->$attribute = $this->decrypt($this->owner->$attribute);
        }
    }

    private function encrypt($value)
    {
        if (!$value) {
            return null;
        }
        return base64_encode(
            \Yii::$app->security->encryptByKey(
                $value,
                $this->getEncryptionKey()
            )
        );
    }

    private function decrypt($encryptedValue)
    {
        if (!$encryptedValue) {
            return null;
        }
        return \Yii::$app->security->decryptByKey(
            base64_decode($encryptedValue),
            $this->getEncryptionKey()
        );
    }

    private function getEncryptionKey(): string
    {
        return $this->encryptionKey ?: getenv('COOKIE_VALIDATION_KEY');
    }
}
