<?php
/**
 * PaymentImplementedInfo
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
 * PaymentImplementedInfo Class Doc Comment
 *
 * @category Class
 * @description Информация об исполненных рублевых платежных поручениях клиента
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */
class PaymentImplementedInfo implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'PaymentImplementedInfo';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'amount' => 'float',
        'createDate' => '\DateTime',
        'lastModifyDate' => '\DateTime',
        'number' => 'string',
        'payeeAccount' => 'string',
        'payeeBankBic' => 'string',
        'payeeInn' => 'string',
        'payeeName' => 'string',
        'purpose' => 'string',
        'uuid' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'amount' => null,
        'createDate' => 'date-time',
        'lastModifyDate' => 'date-time',
        'number' => null,
        'payeeAccount' => null,
        'payeeBankBic' => null,
        'payeeInn' => null,
        'payeeName' => null,
        'purpose' => null,
        'uuid' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'amount' => 'amount',
        'createDate' => 'createDate',
        'lastModifyDate' => 'lastModifyDate',
        'number' => 'number',
        'payeeAccount' => 'payeeAccount',
        'payeeBankBic' => 'payeeBankBic',
        'payeeInn' => 'payeeInn',
        'payeeName' => 'payeeName',
        'purpose' => 'purpose',
        'uuid' => 'uuid',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'amount' => 'setAmount',
        'createDate' => 'setCreateDate',
        'lastModifyDate' => 'setLastModifyDate',
        'number' => 'setNumber',
        'payeeAccount' => 'setPayeeAccount',
        'payeeBankBic' => 'setPayeeBankBic',
        'payeeInn' => 'setPayeeInn',
        'payeeName' => 'setPayeeName',
        'purpose' => 'setPurpose',
        'uuid' => 'setUuid',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'amount' => 'getAmount',
        'createDate' => 'getCreateDate',
        'lastModifyDate' => 'getLastModifyDate',
        'number' => 'getNumber',
        'payeeAccount' => 'getPayeeAccount',
        'payeeBankBic' => 'getPayeeBankBic',
        'payeeInn' => 'getPayeeInn',
        'payeeName' => 'getPayeeName',
        'purpose' => 'getPurpose',
        'uuid' => 'getUuid',
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
        $this->container['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->container['createDate'] = isset($data['createDate']) ? $data['createDate'] : null;
        $this->container['lastModifyDate'] = isset($data['lastModifyDate']) ? $data['lastModifyDate'] : null;
        $this->container['number'] = isset($data['number']) ? $data['number'] : null;
        $this->container['payeeAccount'] = isset($data['payeeAccount']) ? $data['payeeAccount'] : null;
        $this->container['payeeBankBic'] = isset($data['payeeBankBic']) ? $data['payeeBankBic'] : null;
        $this->container['payeeInn'] = isset($data['payeeInn']) ? $data['payeeInn'] : null;
        $this->container['payeeName'] = isset($data['payeeName']) ? $data['payeeName'] : null;
        $this->container['purpose'] = isset($data['purpose']) ? $data['purpose'] : null;
        $this->container['uuid'] = isset($data['uuid']) ? $data['uuid'] : null;
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
     * Gets amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->container['amount'];
    }

    /**
     * Sets amount
     *
     * @param float $amount Сумма документа
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->container['amount'] = $amount;

        return $this;
    }

    /**
     * Gets createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->container['createDate'];
    }

    /**
     * Sets createDate
     *
     * @param \DateTime $createDate Дата и время создания документа
     *
     * @return $this
     */
    public function setCreateDate($createDate)
    {
        $this->container['createDate'] = $createDate;

        return $this;
    }

    /**
     * Gets lastModifyDate
     *
     * @return \DateTime
     */
    public function getLastModifyDate()
    {
        return $this->container['lastModifyDate'];
    }

    /**
     * Sets lastModifyDate
     *
     * @param \DateTime $lastModifyDate Дата и время последней модификации документа
     *
     * @return $this
     */
    public function setLastModifyDate($lastModifyDate)
    {
        $this->container['lastModifyDate'] = $lastModifyDate;

        return $this;
    }

    /**
     * Gets number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->container['number'];
    }

    /**
     * Sets number
     *
     * @param string $number Номер документа
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->container['number'] = $number;

        return $this;
    }

    /**
     * Gets payeeAccount
     *
     * @return string
     */
    public function getPayeeAccount()
    {
        return $this->container['payeeAccount'];
    }

    /**
     * Sets payeeAccount
     *
     * @param string $payeeAccount Счёт получателя
     *
     * @return $this
     */
    public function setPayeeAccount($payeeAccount)
    {
        $this->container['payeeAccount'] = $payeeAccount;

        return $this;
    }

    /**
     * Gets payeeBankBic
     *
     * @return string
     */
    public function getPayeeBankBic()
    {
        return $this->container['payeeBankBic'];
    }

    /**
     * Sets payeeBankBic
     *
     * @param string $payeeBankBic БИК банка получателя
     *
     * @return $this
     */
    public function setPayeeBankBic($payeeBankBic)
    {
        $this->container['payeeBankBic'] = $payeeBankBic;

        return $this;
    }

    /**
     * Gets payeeInn
     *
     * @return string
     */
    public function getPayeeInn()
    {
        return $this->container['payeeInn'];
    }

    /**
     * Sets payeeInn
     *
     * @param string $payeeInn ИНН получателя
     *
     * @return $this
     */
    public function setPayeeInn($payeeInn)
    {
        $this->container['payeeInn'] = $payeeInn;

        return $this;
    }

    /**
     * Gets payeeName
     *
     * @return string
     */
    public function getPayeeName()
    {
        return $this->container['payeeName'];
    }

    /**
     * Sets payeeName
     *
     * @param string $payeeName Наименование организации получателя
     *
     * @return $this
     */
    public function setPayeeName($payeeName)
    {
        $this->container['payeeName'] = $payeeName;

        return $this;
    }

    /**
     * Gets purpose
     *
     * @return string
     */
    public function getPurpose()
    {
        return $this->container['purpose'];
    }

    /**
     * Sets purpose
     *
     * @param string $purpose Назначение платежа
     *
     * @return $this
     */
    public function setPurpose($purpose)
    {
        $this->container['purpose'] = $purpose;

        return $this;
    }

    /**
     * Gets uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->container['uuid'];
    }

    /**
     * Sets uuid
     *
     * @param string $uuid UUID документа
     *
     * @return $this
     */
    public function setUuid($uuid)
    {
        $this->container['uuid'] = $uuid;

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
