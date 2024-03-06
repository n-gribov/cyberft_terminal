<?php
/**
 * PayDoc
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
 * PayDoc Class Doc Comment
 *
 * @category Class
 * @description Прикреплённое платежное поручение для зарплатной ведомости
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */
class PayDoc implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'PayDoc';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'amount' => '\common\models\sbbol2\Amount',
        'docDate' => '\DateTime',
        'number' => 'string',
        'payeeAccount' => 'string',
        'payeeBic' => 'string',
        'payerAccount' => 'string',
        'payerBic' => 'string',
        'purpose' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'amount' => null,
        'docDate' => 'date-time',
        'number' => null,
        'payeeAccount' => null,
        'payeeBic' => null,
        'payerAccount' => null,
        'payerBic' => null,
        'purpose' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'amount' => 'amount',
        'docDate' => 'docDate',
        'number' => 'number',
        'payeeAccount' => 'payeeAccount',
        'payeeBic' => 'payeeBic',
        'payerAccount' => 'payerAccount',
        'payerBic' => 'payerBic',
        'purpose' => 'purpose',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'amount' => 'setAmount',
        'docDate' => 'setDocDate',
        'number' => 'setNumber',
        'payeeAccount' => 'setPayeeAccount',
        'payeeBic' => 'setPayeeBic',
        'payerAccount' => 'setPayerAccount',
        'payerBic' => 'setPayerBic',
        'purpose' => 'setPurpose',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'amount' => 'getAmount',
        'docDate' => 'getDocDate',
        'number' => 'getNumber',
        'payeeAccount' => 'getPayeeAccount',
        'payeeBic' => 'getPayeeBic',
        'payerAccount' => 'getPayerAccount',
        'payerBic' => 'getPayerBic',
        'purpose' => 'getPurpose',
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
        $this->container['docDate'] = isset($data['docDate']) ? $data['docDate'] : null;
        $this->container['number'] = isset($data['number']) ? $data['number'] : null;
        $this->container['payeeAccount'] = isset($data['payeeAccount']) ? $data['payeeAccount'] : null;
        $this->container['payeeBic'] = isset($data['payeeBic']) ? $data['payeeBic'] : null;
        $this->container['payerAccount'] = isset($data['payerAccount']) ? $data['payerAccount'] : null;
        $this->container['payerBic'] = isset($data['payerBic']) ? $data['payerBic'] : null;
        $this->container['purpose'] = isset($data['purpose']) ? $data['purpose'] : null;
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
        $invalidProperties = [];

        if ($this->container['amount'] === null) {
            $invalidProperties[] = "'amount' can't be null";
        }
        if ($this->container['docDate'] === null) {
            $invalidProperties[] = "'docDate' can't be null";
        }
        if ($this->container['number'] === null) {
            $invalidProperties[] = "'number' can't be null";
        }
        if ($this->container['payeeAccount'] === null) {
            $invalidProperties[] = "'payeeAccount' can't be null";
        }
        if ($this->container['payeeBic'] === null) {
            $invalidProperties[] = "'payeeBic' can't be null";
        }
        if ($this->container['payerAccount'] === null) {
            $invalidProperties[] = "'payerAccount' can't be null";
        }
        if ($this->container['payerBic'] === null) {
            $invalidProperties[] = "'payerBic' can't be null";
        }
        if ($this->container['purpose'] === null) {
            $invalidProperties[] = "'purpose' can't be null";
        }

        return $invalidProperties;
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
     * @return \common\models\sbbol2\Amount
     */
    public function getAmount()
    {
        return $this->container['amount'];
    }

    /**
     * Sets amount
     *
     * @param \common\models\sbbol2\Amount $amount amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->container['amount'] = $amount;

        return $this;
    }

    /**
     * Gets docDate
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->container['docDate'];
    }

    /**
     * Sets docDate
     *
     * @param \DateTime $docDate Дата расчетного документа
     *
     * @return $this
     */
    public function setDocDate($docDate)
    {
        $this->container['docDate'] = $docDate;

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
     * @param string $number Номер расчетного документа
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
     * @param string $payeeAccount Номер счета получателя
     *
     * @return $this
     */
    public function setPayeeAccount($payeeAccount)
    {
        $this->container['payeeAccount'] = $payeeAccount;

        return $this;
    }

    /**
     * Gets payeeBic
     *
     * @return string
     */
    public function getPayeeBic()
    {
        return $this->container['payeeBic'];
    }

    /**
     * Sets payeeBic
     *
     * @param string $payeeBic БИК банка получателя
     *
     * @return $this
     */
    public function setPayeeBic($payeeBic)
    {
        $this->container['payeeBic'] = $payeeBic;

        return $this;
    }

    /**
     * Gets payerAccount
     *
     * @return string
     */
    public function getPayerAccount()
    {
        return $this->container['payerAccount'];
    }

    /**
     * Sets payerAccount
     *
     * @param string $payerAccount Номер счета плательщика
     *
     * @return $this
     */
    public function setPayerAccount($payerAccount)
    {
        $this->container['payerAccount'] = $payerAccount;

        return $this;
    }

    /**
     * Gets payerBic
     *
     * @return string
     */
    public function getPayerBic()
    {
        return $this->container['payerBic'];
    }

    /**
     * Sets payerBic
     *
     * @param string $payerBic БИК банка плательщика
     *
     * @return $this
     */
    public function setPayerBic($payerBic)
    {
        $this->container['payerBic'] = $payerBic;

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
     * @param string $purpose Назначение платежного документа
     *
     * @return $this
     */
    public function setPurpose($purpose)
    {
        $this->container['purpose'] = $purpose;

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
