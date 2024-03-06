<?php
/**
 * SalaryAgreementAdmissionValueType
 *
 * PHP version 5
 *
 * @category Class
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */

/**
 * FINTECH
 *
 * Документация FINTECH REST API
 *
 * OpenAPI spec version: 1
 *
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 3.0.8
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace common\models\sbbol2;

use ArrayAccess;

/**
 * SalaryAgreementAdmissionValueType Class Doc Comment
 *
 * @category Class
 * @description Вид зачисления
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */
class SalaryAgreementAdmissionValueType implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'SalaryAgreementAdmissionValueType';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'admissionCode' => 'string',
        'admissionName' => 'string',
        'admissionType' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'admissionCode' => null,
        'admissionName' => null,
        'admissionType' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'admissionCode' => 'admissionCode',
        'admissionName' => 'admissionName',
        'admissionType' => 'admissionType',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'admissionCode' => 'setAdmissionCode',
        'admissionName' => 'setAdmissionName',
        'admissionType' => 'setAdmissionType',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'admissionCode' => 'getAdmissionCode',
        'admissionName' => 'getAdmissionName',
        'admissionType' => 'getAdmissionType',
    ];

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['admissionCode'] = isset($data['admissionCode']) ? $data['admissionCode'] : null;
        $this->container['admissionName'] = isset($data['admissionName']) ? $data['admissionName'] : null;
        $this->container['admissionType'] = isset($data['admissionType']) ? $data['admissionType'] : null;
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        return [];
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }

    /**
     * Gets admissionCode
     *
     * @return string
     */
    public function getAdmissionCode()
    {
        return $this->container['admissionCode'];
    }

    /**
     * Sets admissionCode
     *
     * @param string $admissionCode Код зачисления
     *
     * @return $this
     */
    public function setAdmissionCode($admissionCode)
    {
        $this->container['admissionCode'] = $admissionCode;

        return $this;
    }

    /**
     * Gets admissionName
     *
     * @return string
     */
    public function getAdmissionName()
    {
        return $this->container['admissionName'];
    }

    /**
     * Sets admissionName
     *
     * @param string $admissionName Наименование зачисления
     *
     * @return $this
     */
    public function setAdmissionName($admissionName)
    {
        $this->container['admissionName'] = $admissionName;

        return $this;
    }

    /**
     * Gets admissionType
     *
     * @return string
     */
    public function getAdmissionType()
    {
        return $this->container['admissionType'];
    }

    /**
     * Sets admissionType
     *
     * @param string $admissionType Тип зачисления
     *
     * @return $this
     */
    public function setAdmissionType($admissionType)
    {
        $this->container['admissionType'] = $admissionType;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param int $offset Offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param int $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int   $offset Offset
     * @param mixed $value  Value to be set
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param int $offset Offset
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
}