<?php
/**
 * SalaryAgreementRequest
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
 * SalaryAgreementRequest Class Doc Comment
 *
 * @category Class
 * @description Заявка на удаленное подключение «Зарплатного проекта»
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */
class SalaryAgreementRequest implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'SalaryAgreementRequest';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'account' => 'string',
        'admissionType' => 'string',
        'amount' => 'float',
        'authPersonName' => 'string',
        'authPersonTel' => 'string',
        'bankComment' => 'string',
        'bankStatus' => 'string',
        'bic' => 'string',
        'date' => '\DateTime',
        'digestSignatures' => '\common\models\sbbol2\Signature[]',
        'employeesNumber' => 'int',
        'externalId' => 'string',
        'identityDoc' => '\common\models\sbbol2\SalaryAgreementRequestIdentityDoc',
        'number' => 'string',
        'offerAgree' => 'bool',
        'orgName' => 'string',
        'orgTaxNumber' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'account' => null,
        'admissionType' => null,
        'amount' => null,
        'authPersonName' => null,
        'authPersonTel' => null,
        'bankComment' => null,
        'bankStatus' => null,
        'bic' => null,
        'date' => 'date-time',
        'digestSignatures' => null,
        'employeesNumber' => 'int32',
        'externalId' => null,
        'identityDoc' => null,
        'number' => null,
        'offerAgree' => null,
        'orgName' => null,
        'orgTaxNumber' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'account' => 'account',
        'admissionType' => 'admissionType',
        'amount' => 'amount',
        'authPersonName' => 'authPersonName',
        'authPersonTel' => 'authPersonTel',
        'bankComment' => 'bankComment',
        'bankStatus' => 'bankStatus',
        'bic' => 'bic',
        'date' => 'date',
        'digestSignatures' => 'digestSignatures',
        'employeesNumber' => 'employeesNumber',
        'externalId' => 'externalId',
        'identityDoc' => 'identityDoc',
        'number' => 'number',
        'offerAgree' => 'offerAgree',
        'orgName' => 'orgName',
        'orgTaxNumber' => 'orgTaxNumber',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'account' => 'setAccount',
        'admissionType' => 'setAdmissionType',
        'amount' => 'setAmount',
        'authPersonName' => 'setAuthPersonName',
        'authPersonTel' => 'setAuthPersonTel',
        'bankComment' => 'setBankComment',
        'bankStatus' => 'setBankStatus',
        'bic' => 'setBic',
        'date' => 'setDate',
        'digestSignatures' => 'setDigestSignatures',
        'employeesNumber' => 'setEmployeesNumber',
        'externalId' => 'setExternalId',
        'identityDoc' => 'setIdentityDoc',
        'number' => 'setNumber',
        'offerAgree' => 'setOfferAgree',
        'orgName' => 'setOrgName',
        'orgTaxNumber' => 'setOrgTaxNumber',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'account' => 'getAccount',
        'admissionType' => 'getAdmissionType',
        'amount' => 'getAmount',
        'authPersonName' => 'getAuthPersonName',
        'authPersonTel' => 'getAuthPersonTel',
        'bankComment' => 'getBankComment',
        'bankStatus' => 'getBankStatus',
        'bic' => 'getBic',
        'date' => 'getDate',
        'digestSignatures' => 'getDigestSignatures',
        'employeesNumber' => 'getEmployeesNumber',
        'externalId' => 'getExternalId',
        'identityDoc' => 'getIdentityDoc',
        'number' => 'getNumber',
        'offerAgree' => 'getOfferAgree',
        'orgName' => 'getOrgName',
        'orgTaxNumber' => 'getOrgTaxNumber',
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
        $this->container['account'] = isset($data['account']) ? $data['account'] : null;
        $this->container['admissionType'] = isset($data['admissionType']) ? $data['admissionType'] : null;
        $this->container['amount'] = isset($data['amount']) ? $data['amount'] : null;
        $this->container['authPersonName'] = isset($data['authPersonName']) ? $data['authPersonName'] : null;
        $this->container['authPersonTel'] = isset($data['authPersonTel']) ? $data['authPersonTel'] : null;
        $this->container['bankComment'] = isset($data['bankComment']) ? $data['bankComment'] : null;
        $this->container['bankStatus'] = isset($data['bankStatus']) ? $data['bankStatus'] : null;
        $this->container['bic'] = isset($data['bic']) ? $data['bic'] : null;
        $this->container['date'] = isset($data['date']) ? $data['date'] : null;
        $this->container['digestSignatures'] = isset($data['digestSignatures']) ? $data['digestSignatures'] : null;
        $this->container['employeesNumber'] = isset($data['employeesNumber']) ? $data['employeesNumber'] : null;
        $this->container['externalId'] = isset($data['externalId']) ? $data['externalId'] : null;
        $this->container['identityDoc'] = isset($data['identityDoc']) ? $data['identityDoc'] : null;
        $this->container['number'] = isset($data['number']) ? $data['number'] : null;
        $this->container['offerAgree'] = isset($data['offerAgree']) ? $data['offerAgree'] : null;
        $this->container['orgName'] = isset($data['orgName']) ? $data['orgName'] : null;
        $this->container['orgTaxNumber'] = isset($data['orgTaxNumber']) ? $data['orgTaxNumber'] : null;
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

        if ($this->container['account'] === null) {
            $invalidProperties[] = "'account' can't be null";
        }
        if ($this->container['admissionType'] === null) {
            $invalidProperties[] = "'admissionType' can't be null";
        }
        if ($this->container['amount'] === null) {
            $invalidProperties[] = "'amount' can't be null";
        }
        if ($this->container['authPersonName'] === null) {
            $invalidProperties[] = "'authPersonName' can't be null";
        }
        if ($this->container['authPersonTel'] === null) {
            $invalidProperties[] = "'authPersonTel' can't be null";
        }
        if ($this->container['bic'] === null) {
            $invalidProperties[] = "'bic' can't be null";
        }
        if ($this->container['date'] === null) {
            $invalidProperties[] = "'date' can't be null";
        }
        if ($this->container['employeesNumber'] === null) {
            $invalidProperties[] = "'employeesNumber' can't be null";
        }
        if ($this->container['externalId'] === null) {
            $invalidProperties[] = "'externalId' can't be null";
        }
        if ($this->container['identityDoc'] === null) {
            $invalidProperties[] = "'identityDoc' can't be null";
        }
        if ($this->container['orgName'] === null) {
            $invalidProperties[] = "'orgName' can't be null";
        }
        if ($this->container['orgTaxNumber'] === null) {
            $invalidProperties[] = "'orgTaxNumber' can't be null";
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
     * Gets account
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->container['account'];
    }

    /**
     * Sets account
     *
     * @param string $account Номер счёта клиента
     *
     * @return $this
     */
    public function setAccount($account)
    {
        $this->container['account'] = $account;

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
     * @param float $amount Месячный фонд оплаты труда
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->container['amount'] = $amount;

        return $this;
    }

    /**
     * Gets authPersonName
     *
     * @return string
     */
    public function getAuthPersonName()
    {
        return $this->container['authPersonName'];
    }

    /**
     * Sets authPersonName
     *
     * @param string $authPersonName ФИО уполномоченного сотрудника организации клиента
     *
     * @return $this
     */
    public function setAuthPersonName($authPersonName)
    {
        $this->container['authPersonName'] = $authPersonName;

        return $this;
    }

    /**
     * Gets authPersonTel
     *
     * @return string
     */
    public function getAuthPersonTel()
    {
        return $this->container['authPersonTel'];
    }

    /**
     * Sets authPersonTel
     *
     * @param string $authPersonTel Номер телефона уполномоченного сотрудника организации клиента
     *
     * @return $this
     */
    public function setAuthPersonTel($authPersonTel)
    {
        $this->container['authPersonTel'] = $authPersonTel;

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
     * Gets bic
     *
     * @return string
     */
    public function getBic()
    {
        return $this->container['bic'];
    }

    /**
     * Sets bic
     *
     * @param string $bic БИК банка клиента
     *
     * @return $this
     */
    public function setBic($bic)
    {
        $this->container['bic'] = $bic;

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
     * Gets employeesNumber
     *
     * @return int
     */
    public function getEmployeesNumber()
    {
        return $this->container['employeesNumber'];
    }

    /**
     * Sets employeesNumber
     *
     * @param int $employeesNumber Количество сотрудников
     *
     * @return $this
     */
    public function setEmployeesNumber($employeesNumber)
    {
        $this->container['employeesNumber'] = $employeesNumber;

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
     * Gets identityDoc
     *
     * @return \common\models\sbbol2\SalaryAgreementRequestIdentityDoc
     */
    public function getIdentityDoc()
    {
        return $this->container['identityDoc'];
    }

    /**
     * Sets identityDoc
     *
     * @param \common\models\sbbol2\SalaryAgreementRequestIdentityDoc $identityDoc identityDoc
     *
     * @return $this
     */
    public function setIdentityDoc($identityDoc)
    {
        $this->container['identityDoc'] = $identityDoc;

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
     * Gets offerAgree
     *
     * @return bool
     */
    public function getOfferAgree()
    {
        return $this->container['offerAgree'];
    }

    /**
     * Sets offerAgree
     *
     * @param bool $offerAgree Согласие с Условиями предоставления услуг и Тарифами
     *
     * @return $this
     */
    public function setOfferAgree($offerAgree)
    {
        $this->container['offerAgree'] = $offerAgree;

        return $this;
    }

    /**
     * Gets orgName
     *
     * @return string
     */
    public function getOrgName()
    {
        return $this->container['orgName'];
    }

    /**
     * Sets orgName
     *
     * @param string $orgName Наименование организации клиента
     *
     * @return $this
     */
    public function setOrgName($orgName)
    {
        $this->container['orgName'] = $orgName;

        return $this;
    }

    /**
     * Gets orgTaxNumber
     *
     * @return string
     */
    public function getOrgTaxNumber()
    {
        return $this->container['orgTaxNumber'];
    }

    /**
     * Sets orgTaxNumber
     *
     * @param string $orgTaxNumber ИНН клиента
     *
     * @return $this
     */
    public function setOrgTaxNumber($orgTaxNumber)
    {
        $this->container['orgTaxNumber'] = $orgTaxNumber;

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