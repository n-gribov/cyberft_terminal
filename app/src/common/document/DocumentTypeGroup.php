<?php

namespace common\document;

use common\document\DocumentTypeGroup\Discriminator;
use yii\base\BaseObject;

class DocumentTypeGroup extends BaseObject
{
    public $id;
    public $types = [];
    public $name;
    public $availablePermissions = [];
    public $discriminators = [];

    public function hasPermission($permissionCode)
    {
        return in_array($permissionCode, $this->availablePermissions);
    }

    public function matchesDocument(string $documentType, array $extModelAttributes): bool
    {
        if ($this->hasDiscriminator($documentType)) {
            return $this->getDiscriminator($documentType)->match($extModelAttributes);
        }
        return in_array($documentType, $this->types);
    }

    public function hasDiscriminator(string $documentType): bool
    {
        return array_key_exists($documentType, $this->discriminators);
    }

    /**
     * @param string $documentType
     * @return Discriminator|null
     */
    public function getDiscriminator(string $documentType)
    {
        if ($this->hasDiscriminator($documentType)) {
            return new Discriminator($this->discriminators[$documentType]);
        }
        return null;
    }
}
