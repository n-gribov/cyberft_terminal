<?php


namespace addons\VTB\models\soap\messages;


class BaseMessage
{
    public function __construct($properties  = [])
    {
        foreach ($properties as $property => $value) {
            $this->setProperty($property, $value);
        }
    }

    public function getPropertiesAsArray()
    {
        $class = new \ReflectionClass($this);
        return array_map(
            function (\ReflectionProperty $property) {
                $propertyName = $property->getName();
                return $this->$propertyName;
            },
            $class->getProperties()
        );
    }

    protected function checkPropertyExists($property)
    {
        if (!property_exists(static::class, $property)) {
            throw new \Exception(sprintf('Class %s has no "%s" property', static::class, $property));
        }
    }

    protected function setProperty($property, $value)
    {
        $this->checkPropertyExists($property);
        $this->$property = $value;
    }
}
