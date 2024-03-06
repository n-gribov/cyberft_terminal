<?php

namespace common\components\processingApi\models;

use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

abstract class JsonSerializableObject extends BaseObject
{
    protected const PROPERTY_TYPE_ANY = 'any';
    protected const PROPERTY_TYPE_DATE = 'date';
    protected const PROPERTY_TYPE_DATETIME = 'datetime';
    private const DATE_FORMAT = 'Y-m-d';
    private const DATETIME_FORMAT = 'Y-m-d h:i:s';

    abstract protected function mapping(): array;

    protected function propertyTypes(): array
    {
        return [];
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        $instance = new static();
        foreach ($instance->mapping() as $property => $path) {
            $value = ArrayHelper::getValue($data, $path);
            $instance->deserializeProperty($property, $value);
        }
        return $instance;
    }

    public function toJson(): string
    {
        $data = [];
        foreach ($this->mapping() as $property => $path) {
            ArrayHelper::setValue($data, $path, $this->serializeProperty($property));
        }
        return json_encode($data);
    }

    private function getPropertyType(string $property): string
    {
        return ArrayHelper::getValue($this->propertyTypes(), $property, self::PROPERTY_TYPE_ANY);
    }

    private function serializeProperty(string $property)
    {
        $value = $this->$property;
        if ($value === null) {
            return null;
        }

        $propertyType = $this->getPropertyType($property);
        switch ($propertyType) {
            case self::PROPERTY_TYPE_ANY:
                return $value;
            case self::PROPERTY_TYPE_DATE:
                /** @var \DateTimeInterface $value */
                return $value->format(self::DATE_FORMAT);
            case self::PROPERTY_TYPE_DATETIME:
                /** @var \DateTimeInterface $value */
                return $value->format(self::DATETIME_FORMAT);
            default:
                throw new \Exception("Unsupported property format: $propertyType");
        }
    }

    private function deserializeProperty(string $property, $value): void
    {
        $propertyType = $this->getPropertyType($property);
        switch ($propertyType) {
            case self::PROPERTY_TYPE_ANY:
                $this->$property = $value;
                break;
            case self::PROPERTY_TYPE_DATE:
            case self::PROPERTY_TYPE_DATETIME:
                $this->$property = $value === null ? null : new \DateTime($value);
                break;
            default:
                throw new \Exception("Unsupported property format: $propertyType");
        }
    }
}
