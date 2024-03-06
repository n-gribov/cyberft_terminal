<?php
/**
 * CardIssueAddress
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
 * CardIssueAddress Class Doc Comment
 *
 * @category Class
 * @description Адрес
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */
class CardIssueAddress implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'CardIssueAddress';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'building' => 'string',
        'city' => 'string',
        'country' => 'string',
        'countryCode' => 'string',
        'countryNumericCode' => 'string',
        'district' => 'string',
        'flat' => 'string',
        'fullAddress' => 'string',
        'house' => 'string',
        'postalCode' => 'string',
        'settlementName' => 'string',
        'state' => 'string',
        'street' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'building' => null,
        'city' => null,
        'country' => null,
        'countryCode' => null,
        'countryNumericCode' => null,
        'district' => null,
        'flat' => null,
        'fullAddress' => null,
        'house' => null,
        'postalCode' => null,
        'settlementName' => null,
        'state' => null,
        'street' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'building' => 'building',
        'city' => 'city',
        'country' => 'country',
        'countryCode' => 'countryCode',
        'countryNumericCode' => 'countryNumericCode',
        'district' => 'district',
        'flat' => 'flat',
        'fullAddress' => 'fullAddress',
        'house' => 'house',
        'postalCode' => 'postalCode',
        'settlementName' => 'settlementName',
        'state' => 'state',
        'street' => 'street',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'building' => 'setBuilding',
        'city' => 'setCity',
        'country' => 'setCountry',
        'countryCode' => 'setCountryCode',
        'countryNumericCode' => 'setCountryNumericCode',
        'district' => 'setDistrict',
        'flat' => 'setFlat',
        'fullAddress' => 'setFullAddress',
        'house' => 'setHouse',
        'postalCode' => 'setPostalCode',
        'settlementName' => 'setSettlementName',
        'state' => 'setState',
        'street' => 'setStreet',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'building' => 'getBuilding',
        'city' => 'getCity',
        'country' => 'getCountry',
        'countryCode' => 'getCountryCode',
        'countryNumericCode' => 'getCountryNumericCode',
        'district' => 'getDistrict',
        'flat' => 'getFlat',
        'fullAddress' => 'getFullAddress',
        'house' => 'getHouse',
        'postalCode' => 'getPostalCode',
        'settlementName' => 'getSettlementName',
        'state' => 'getState',
        'street' => 'getStreet',
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
        $this->container['building'] = isset($data['building']) ? $data['building'] : null;
        $this->container['city'] = isset($data['city']) ? $data['city'] : null;
        $this->container['country'] = isset($data['country']) ? $data['country'] : null;
        $this->container['countryCode'] = isset($data['countryCode']) ? $data['countryCode'] : null;
        $this->container['countryNumericCode'] = isset($data['countryNumericCode']) ? $data['countryNumericCode'] : null;
        $this->container['district'] = isset($data['district']) ? $data['district'] : null;
        $this->container['flat'] = isset($data['flat']) ? $data['flat'] : null;
        $this->container['fullAddress'] = isset($data['fullAddress']) ? $data['fullAddress'] : null;
        $this->container['house'] = isset($data['house']) ? $data['house'] : null;
        $this->container['postalCode'] = isset($data['postalCode']) ? $data['postalCode'] : null;
        $this->container['settlementName'] = isset($data['settlementName']) ? $data['settlementName'] : null;
        $this->container['state'] = isset($data['state']) ? $data['state'] : null;
        $this->container['street'] = isset($data['street']) ? $data['street'] : null;
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

        if ($this->container['country'] === null) {
            $invalidProperties[] = "'country' can't be null";
        }
        if ($this->container['countryCode'] === null) {
            $invalidProperties[] = "'countryCode' can't be null";
        }
        if ($this->container['countryNumericCode'] === null) {
            $invalidProperties[] = "'countryNumericCode' can't be null";
        }
        if ($this->container['house'] === null) {
            $invalidProperties[] = "'house' can't be null";
        }
        if ($this->container['postalCode'] === null) {
            $invalidProperties[] = "'postalCode' can't be null";
        }
        if ($this->container['state'] === null) {
            $invalidProperties[] = "'state' can't be null";
        }
        if ($this->container['street'] === null) {
            $invalidProperties[] = "'street' can't be null";
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
     * Gets building
     *
     * @return string
     */
    public function getBuilding()
    {
        return $this->container['building'];
    }

    /**
     * Sets building
     *
     * @param string $building Номер корпуса
     *
     * @return $this
     */
    public function setBuilding($building)
    {
        $this->container['building'] = $building;

        return $this;
    }

    /**
     * Gets city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->container['city'];
    }

    /**
     * Sets city
     *
     * @param string $city Город
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->container['city'] = $city;

        return $this;
    }

    /**
     * Gets country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->container['country'];
    }

    /**
     * Sets country
     *
     * @param string $country Наименование страны
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->container['country'] = $country;

        return $this;
    }

    /**
     * Gets countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->container['countryCode'];
    }

    /**
     * Sets countryCode
     *
     * @param string $countryCode Трехбуквенный код страны
     *
     * @return $this
     */
    public function setCountryCode($countryCode)
    {
        $this->container['countryCode'] = $countryCode;

        return $this;
    }

    /**
     * Gets countryNumericCode
     *
     * @return string
     */
    public function getCountryNumericCode()
    {
        return $this->container['countryNumericCode'];
    }

    /**
     * Sets countryNumericCode
     *
     * @param string $countryNumericCode Цифровой код страны
     *
     * @return $this
     */
    public function setCountryNumericCode($countryNumericCode)
    {
        $this->container['countryNumericCode'] = $countryNumericCode;

        return $this;
    }

    /**
     * Gets district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->container['district'];
    }

    /**
     * Sets district
     *
     * @param string $district Район
     *
     * @return $this
     */
    public function setDistrict($district)
    {
        $this->container['district'] = $district;

        return $this;
    }

    /**
     * Gets flat
     *
     * @return string
     */
    public function getFlat()
    {
        return $this->container['flat'];
    }

    /**
     * Sets flat
     *
     * @param string $flat Номер офиса/квартиры
     *
     * @return $this
     */
    public function setFlat($flat)
    {
        $this->container['flat'] = $flat;

        return $this;
    }

    /**
     * Gets fullAddress
     *
     * @return string
     */
    public function getFullAddress()
    {
        return $this->container['fullAddress'];
    }

    /**
     * Sets fullAddress
     *
     * @param string $fullAddress Полный адрес
     *
     * @return $this
     */
    public function setFullAddress($fullAddress)
    {
        $this->container['fullAddress'] = $fullAddress;

        return $this;
    }

    /**
     * Gets house
     *
     * @return string
     */
    public function getHouse()
    {
        return $this->container['house'];
    }

    /**
     * Sets house
     *
     * @param string $house Номер дома
     *
     * @return $this
     */
    public function setHouse($house)
    {
        $this->container['house'] = $house;

        return $this;
    }

    /**
     * Gets postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->container['postalCode'];
    }

    /**
     * Sets postalCode
     *
     * @param string $postalCode Индекс
     *
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->container['postalCode'] = $postalCode;

        return $this;
    }

    /**
     * Gets settlementName
     *
     * @return string
     */
    public function getSettlementName()
    {
        return $this->container['settlementName'];
    }

    /**
     * Sets settlementName
     *
     * @param string $settlementName Наименование нас. пункта
     *
     * @return $this
     */
    public function setSettlementName($settlementName)
    {
        $this->container['settlementName'] = $settlementName;

        return $this;
    }

    /**
     * Gets state
     *
     * @return string
     */
    public function getState()
    {
        return $this->container['state'];
    }

    /**
     * Sets state
     *
     * @param string $state Субъект/Регион
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->container['state'] = $state;

        return $this;
    }

    /**
     * Gets street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->container['street'];
    }

    /**
     * Sets street
     *
     * @param string $street Улица
     *
     * @return $this
     */
    public function setStreet($street)
    {
        $this->container['street'] = $street;

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
