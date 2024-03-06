<?php
/**
 * PeriodicPayment
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
 * PeriodicPayment Class Doc Comment
 *
 * @category Class
 * @description Длительное платёжное поручение (ДПП)
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */
class PeriodicPayment implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    const AMOUNT_MODE_AMOUNT = 'AMOUNT';
    const AMOUNT_MODE_REMAINDER = 'REMAINDER';

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'PeriodicPayment';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'amount' => 'float',
        'amountMode' => 'string',
        'bankComment' => 'string',
        'bankStatus' => 'string',
        'date' => '\DateTime',
        'digestSignatures' => '\common\models\sbbol2\Signature[]',
        'externalId' => 'string',
        'minimumBalance' => 'float',
        'number' => 'string',
        'payeeAccount' => 'string',
        'payeeBankBic' => 'string',
        'payeeInn' => 'string',
        'payeeKpp' => 'string',
        'payeeName' => 'string',
        'payerAccount' => 'string',
        'payerBankBic' => 'string',
        'payerName' => 'string',
        'periodicity' => '\common\models\sbbol2\Periodicity',
        'purpose' => 'string',
        'startDate' => '\DateTime',
        'vat' => '\common\models\sbbol2\Vat',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'amount' => null,
        'amountMode' => null,
        'bankComment' => null,
        'bankStatus' => null,
        'date' => 'date-time',
        'digestSignatures' => null,
        'externalId' => null,
        'minimumBalance' => null,
        'number' => null,
        'payeeAccount' => null,
        'payeeBankBic' => null,
        'payeeInn' => null,
        'payeeKpp' => null,
        'payeeName' => null,
        'payerAccount' => null,
        'payerBankBic' => null,
        'payerName' => null,
        'periodicity' => null,
        'purpose' => null,
        'startDate' => 'date-time',
        'vat' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'amount' => 'amount',
        'amountMode' => 'amountMode',
        'bankComment' => 'bankComment',
        'bankStatus' => 'bankStatus',
        'date' => 'date',
        'digestSignatures' => 'digestSignatures',
        'externalId' => 'externalId',
        'minimumBalance' => 'minimumBalance',
        'number' => 'number',
        'payeeAccount' => 'payeeAccount',
        'payeeBankBic' => 'payeeBankBic',
        'payeeInn' => 'payeeInn',
        'payeeKpp' => 'payeeKpp',
        'payeeName' => 'payeeName',
        'payerAccount' => 'payerAccount',
        'payerBankBic' => 'payerBankBic',
        'payerName' => 'payerName',
        'periodicity' => 'periodicity',
        'purpose' => 'purpose',
        'startDate' => 'startDate',
        'vat' => 'vat',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'amount' => 'setAmount',
        'amountMode' => 'setAmountMode',
        'bankComment' => 'setBankComment',
        'bankStatus' => 'setBankStatus',
        'date' => 'setDate',
        'digestSignatures' => 'setDigestSignatures',
        'externalId' => 'setExternalId',
        'minimumBalance' => 'setMinimumBalance',
        'number' => 'setNumber',
        'payeeAccount' => 'setPayeeAccount',
        'payeeBankBic' => 'setPayeeBankBic',
        'payeeInn' => 'setPayeeInn',
        'payeeKpp' => 'setPayeeKpp',
        'payeeName' => 'setPayeeName',
        'payerAccount' => 'setPayerAccount',
        'payerBankBic' => 'setPayerBankBic',
        'payerName' => 'setPayerName',
        'periodicity' => 'setPeriodicity',
        'purpose' => 'setPurpose',
        'startDate' => 'setStartDate',
        'vat' => 'setVat',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'amount' => 'getAmount',
        'amountMode' => 'getAmountMode',
        'bankComment' => 'getBankComment',
        'bankStatus' => 'getBankStatus',
        'date' => 'getDate',
        'digestSignatures' => 'getDigestSignatures',
        'externalId' => 'getExternalId',
        'minimumBalance' => 'getMinimumBalance',
        'number' => 'getNumber',
        'payeeAccount' => 'getPayeeAccount',
        'payeeBankBic' => 'getPayeeBankBic',
        'payeeInn' => 'getPayeeInn',
        'payeeKpp' => 'getPayeeKpp',
        'payeeName' => 'getPayeeName',
        'payerAccount' => 'getPayerAccount',
        'payerBankBic' => 'getPayerBankBic',
        'payerName' => 'getPayerName',
        'periodicity' => 'getPeriodicity',
        'purpose' => 'getPurpose',
        'startDate' => 'getStartDate',
        'vat' => 'getVat',
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
        $this->container['amountMode'] = isset($data['amountMode']) ? $data['amountMode'] : null;
        $this->container['bankComment'] = isset($data['bankComment']) ? $data['bankComment'] : null;
        $this->container['bankStatus'] = isset($data['bankStatus']) ? $data['bankStatus'] : null;
        $this->container['date'] = isset($data['date']) ? $data['date'] : null;
        $this->container['digestSignatures'] = isset($data['digestSignatures']) ? $data['digestSignatures'] : null;
        $this->container['externalId'] = isset($data['externalId']) ? $data['externalId'] : null;
        $this->container['minimumBalance'] = isset($data['minimumBalance']) ? $data['minimumBalance'] : null;
        $this->container['number'] = isset($data['number']) ? $data['number'] : null;
        $this->container['payeeAccount'] = isset($data['payeeAccount']) ? $data['payeeAccount'] : null;
        $this->container['payeeBankBic'] = isset($data['payeeBankBic']) ? $data['payeeBankBic'] : null;
        $this->container['payeeInn'] = isset($data['payeeInn']) ? $data['payeeInn'] : null;
        $this->container['payeeKpp'] = isset($data['payeeKpp']) ? $data['payeeKpp'] : null;
        $this->container['payeeName'] = isset($data['payeeName']) ? $data['payeeName'] : null;
        $this->container['payerAccount'] = isset($data['payerAccount']) ? $data['payerAccount'] : null;
        $this->container['payerBankBic'] = isset($data['payerBankBic']) ? $data['payerBankBic'] : null;
        $this->container['payerName'] = isset($data['payerName']) ? $data['payerName'] : null;
        $this->container['periodicity'] = isset($data['periodicity']) ? $data['periodicity'] : null;
        $this->container['purpose'] = isset($data['purpose']) ? $data['purpose'] : null;
        $this->container['startDate'] = isset($data['startDate']) ? $data['startDate'] : null;
        $this->container['vat'] = isset($data['vat']) ? $data['vat'] : null;
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
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getAmountModeAllowableValues()
    {
        return [
            self::AMOUNT_MODE_AMOUNT,
            self::AMOUNT_MODE_REMAINDER,
        ];
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['amountMode'] === null) {
            $invalidProperties[] = "'amountMode' can't be null";
        }
        $allowedValues = $this->getAmountModeAllowableValues();
        if (!is_null($this->container['amountMode']) && !in_array($this->container['amountMode'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'amountMode', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['date'] === null) {
            $invalidProperties[] = "'date' can't be null";
        }
        if ($this->container['externalId'] === null) {
            $invalidProperties[] = "'externalId' can't be null";
        }
        if ($this->container['payeeBankBic'] === null) {
            $invalidProperties[] = "'payeeBankBic' can't be null";
        }
        if ($this->container['payeeName'] === null) {
            $invalidProperties[] = "'payeeName' can't be null";
        }
        if ($this->container['payerAccount'] === null) {
            $invalidProperties[] = "'payerAccount' can't be null";
        }
        if ($this->container['payerBankBic'] === null) {
            $invalidProperties[] = "'payerBankBic' can't be null";
        }
        if ($this->container['payerName'] === null) {
            $invalidProperties[] = "'payerName' can't be null";
        }
        if ($this->container['periodicity'] === null) {
            $invalidProperties[] = "'periodicity' can't be null";
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
     * @return float
     */
    public function getAmount()
    {
        return $this->container['amount'];
    }

    /**
     * Sets amount
     *
     * @param float $amount Сумма платежа для поручений с режимом AMOUNT
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->container['amount'] = $amount;

        return $this;
    }

    /**
     * Gets amountMode
     *
     * @return string
     */
    public function getAmountMode()
    {
        return $this->container['amountMode'];
    }

    /**
     * Sets amountMode
     *
     * @param string $amountMode Режим определения суммы платежа
     *
     * @return $this
     */
    public function setAmountMode($amountMode)
    {
        $allowedValues = $this->getAmountModeAllowableValues();
        if (!in_array($amountMode, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'amountMode', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['amountMode'] = $amountMode;

        return $this;
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
     * Gets date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->container['date'];
    }

    /**
     * Sets date
     *
     * @param \DateTime $date Дата составления документа
     *
     * @return $this
     */
    public function setDate($date)
    {
        $this->container['date'] = $date;

        return $this;
    }

    /**
     * Gets digestSignatures
     *
     * @return \common\models\sbbol2\Signature[]
     */
    public function getDigestSignatures()
    {
        return $this->container['digestSignatures'];
    }

    /**
     * Sets digestSignatures
     *
     * @param \common\models\sbbol2\Signature[] $digestSignatures Электронные подписи по дайджесту документа
     *
     * @return $this
     */
    public function setDigestSignatures($digestSignatures)
    {
        $this->container['digestSignatures'] = $digestSignatures;

        return $this;
    }

    /**
     * Gets externalId
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->container['externalId'];
    }

    /**
     * Sets externalId
     *
     * @param string $externalId Идентификатор документа, присвоенный партнёром (UUID)
     *
     * @return $this
     */
    public function setExternalId($externalId)
    {
        $this->container['externalId'] = $externalId;

        return $this;
    }

    /**
     * Gets minimumBalance
     *
     * @return float
     */
    public function getMinimumBalance()
    {
        return $this->container['minimumBalance'];
    }

    /**
     * Sets minimumBalance
     *
     * @param float $minimumBalance Неснижаемый остаток для поручений с режимом REMAINDER
     *
     * @return $this
     */
    public function setMinimumBalance($minimumBalance)
    {
        $this->container['minimumBalance'] = $minimumBalance;

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
     * @param string $payeeAccount Счёт получателя платежа
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
     * @param string $payeeBankBic БИК получателя платежа
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
     * @param string $payeeInn ИНН получателя платежа
     *
     * @return $this
     */
    public function setPayeeInn($payeeInn)
    {
        $this->container['payeeInn'] = $payeeInn;

        return $this;
    }

    /**
     * Gets payeeKpp
     *
     * @return string
     */
    public function getPayeeKpp()
    {
        return $this->container['payeeKpp'];
    }

    /**
     * Sets payeeKpp
     *
     * @param string $payeeKpp КПП получателя платежа
     *
     * @return $this
     */
    public function setPayeeKpp($payeeKpp)
    {
        $this->container['payeeKpp'] = $payeeKpp;

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
     * @param string $payeeName Полное наименование получателя платежа
     *
     * @return $this
     */
    public function setPayeeName($payeeName)
    {
        $this->container['payeeName'] = $payeeName;

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
     * @param string $payerAccount Счёт плательщика
     *
     * @return $this
     */
    public function setPayerAccount($payerAccount)
    {
        $this->container['payerAccount'] = $payerAccount;

        return $this;
    }

    /**
     * Gets payerBankBic
     *
     * @return string
     */
    public function getPayerBankBic()
    {
        return $this->container['payerBankBic'];
    }

    /**
     * Sets payerBankBic
     *
     * @param string $payerBankBic БИК банка плательщика
     *
     * @return $this
     */
    public function setPayerBankBic($payerBankBic)
    {
        $this->container['payerBankBic'] = $payerBankBic;

        return $this;
    }

    /**
     * Gets payerName
     *
     * @return string
     */
    public function getPayerName()
    {
        return $this->container['payerName'];
    }

    /**
     * Sets payerName
     *
     * @param string $payerName Полное наименование плательщика
     *
     * @return $this
     */
    public function setPayerName($payerName)
    {
        $this->container['payerName'] = $payerName;

        return $this;
    }

    /**
     * Gets periodicity
     *
     * @return \common\models\sbbol2\Periodicity
     */
    public function getPeriodicity()
    {
        return $this->container['periodicity'];
    }

    /**
     * Sets periodicity
     *
     * @param \common\models\sbbol2\Periodicity $periodicity periodicity
     *
     * @return $this
     */
    public function setPeriodicity($periodicity)
    {
        $this->container['periodicity'] = $periodicity;

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
     * Gets startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->container['startDate'];
    }

    /**
     * Sets startDate
     *
     * @param \DateTime $startDate Дата начала действия
     *
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->container['startDate'] = $startDate;

        return $this;
    }

    /**
     * Gets vat
     *
     * @return \common\models\sbbol2\Vat
     */
    public function getVat()
    {
        return $this->container['vat'];
    }

    /**
     * Sets vat
     *
     * @param \common\models\sbbol2\Vat $vat vat
     *
     * @return $this
     */
    public function setVat($vat)
    {
        $this->container['vat'] = $vat;

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