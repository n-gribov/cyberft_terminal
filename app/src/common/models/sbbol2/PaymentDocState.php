<?php
/**
 * PaymentDocState
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
 * PaymentDocState Class Doc Comment
 *
 * @category Class
 * @description Статус документа
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */
class PaymentDocState implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'PaymentDocState';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'bankComment' => 'string',
        'bankStatus' => 'string',
        'channelInfo' => 'string',
        'crucialFieldsHash' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'bankComment' => null,
        'bankStatus' => null,
        'channelInfo' => null,
        'crucialFieldsHash' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'bankComment' => 'bankComment',
        'bankStatus' => 'bankStatus',
        'channelInfo' => 'channelInfo',
        'crucialFieldsHash' => 'crucialFieldsHash',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'bankComment' => 'setBankComment',
        'bankStatus' => 'setBankStatus',
        'channelInfo' => 'setChannelInfo',
        'crucialFieldsHash' => 'setCrucialFieldsHash',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'bankComment' => 'getBankComment',
        'bankStatus' => 'getBankStatus',
        'channelInfo' => 'getChannelInfo',
        'crucialFieldsHash' => 'getCrucialFieldsHash',
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
        $this->container['bankComment'] = isset($data['bankComment']) ? $data['bankComment'] : null;
        $this->container['bankStatus'] = isset($data['bankStatus']) ? $data['bankStatus'] : null;
        $this->container['channelInfo'] = isset($data['channelInfo']) ? $data['channelInfo'] : null;
        $this->container['crucialFieldsHash'] = isset($data['crucialFieldsHash']) ? $data['crucialFieldsHash'] : null;
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
     * Gets bankComment
     *
     * @return string
     */
    public function getBankComment()
    {
        return $this->container['bankComment'];
    }

    /**
     * Sets bankComment
     *
     * @param string $bankComment Банковский комментарий к статусу документа
     *
     * @return $this
     */
    public function setBankComment($bankComment)
    {
        $this->container['bankComment'] = $bankComment;

        return $this;
    }

    /**
     * Gets bankStatus
     *
     * @return string
     */
    public function getBankStatus()
    {
        return $this->container['bankStatus'];
    }

    /**
     * Sets bankStatus
     *
     * @param string $bankStatus Статус документа
     *
     * @return $this
     */
    public function setBankStatus($bankStatus)
    {
        $this->container['bankStatus'] = $bankStatus;

        return $this;
    }

    /**
     * Gets channelInfo
     *
     * @return string
     */
    public function getChannelInfo()
    {
        return $this->container['channelInfo'];
    }

    /**
     * Sets channelInfo
     *
     * @param string $channelInfo Комментарий, специфичный для документа, полученного по данному каналу
     *
     * @return $this
     */
    public function setChannelInfo($channelInfo)
    {
        $this->container['channelInfo'] = $channelInfo;

        return $this;
    }

    /**
     * Gets crucialFieldsHash
     *
     * @return string
     */
    public function getCrucialFieldsHash()
    {
        return $this->container['crucialFieldsHash'];
    }

    /**
     * Sets crucialFieldsHash
     *
     * @param string $crucialFieldsHash Hash от ключевых полей документа
     *
     * @return $this
     */
    public function setCrucialFieldsHash($crucialFieldsHash)
    {
        $this->container['crucialFieldsHash'] = $crucialFieldsHash;

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