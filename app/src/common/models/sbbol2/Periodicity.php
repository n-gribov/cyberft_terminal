<?php
/**
 * Periodicity
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
 * Periodicity Class Doc Comment
 *
 * @category Class
 * @description Определение периодичности исполнения
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */
class Periodicity implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    const MODE_DAILY = 'DAILY';
    const MODE_MONTHLY_FIRST_WORKDAY = 'MONTHLY_FIRST_WORKDAY';
    const MODE_MONTHLY_LAST_WORKDAY = 'MONTHLY_LAST_WORKDAY';
    const MODE_WORKDAYS_ODD = 'WORKDAYS_ODD';
    const MODE_WORKDAYS_EVEN = 'WORKDAYS_EVEN';
    const MODE_WEEKLY = 'WEEKLY';
    const MODE_MONTHLY_SELECTED = 'MONTHLY_SELECTED';
    const OFFSET_BACKWARD = 'BACKWARD';
    const OFFSET_FORWARD = 'FORWARD';
    const OFFSET_NONE = 'NONE';

    /**
     * The original name of the model.
     *
     * @var string
     */
    protected static $swaggerModelName = 'Periodicity';

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerTypes = [
        'daysOfMonth' => 'string',
        'daysOfWeek' => 'string',
        'mode' => 'string',
        'offset' => 'string',
        'time' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @var string[]
     */
    protected static $swaggerFormats = [
        'daysOfMonth' => null,
        'daysOfWeek' => null,
        'mode' => null,
        'offset' => null,
        'time' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'daysOfMonth' => 'daysOfMonth',
        'daysOfWeek' => 'daysOfWeek',
        'mode' => 'mode',
        'offset' => 'offset',
        'time' => 'time',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'daysOfMonth' => 'setDaysOfMonth',
        'daysOfWeek' => 'setDaysOfWeek',
        'mode' => 'setMode',
        'offset' => 'setOffset',
        'time' => 'setTime',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'daysOfMonth' => 'getDaysOfMonth',
        'daysOfWeek' => 'getDaysOfWeek',
        'mode' => 'getMode',
        'offset' => 'getOffset',
        'time' => 'getTime',
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
        $this->container['daysOfMonth'] = isset($data['daysOfMonth']) ? $data['daysOfMonth'] : null;
        $this->container['daysOfWeek'] = isset($data['daysOfWeek']) ? $data['daysOfWeek'] : null;
        $this->container['mode'] = isset($data['mode']) ? $data['mode'] : null;
        $this->container['offset'] = isset($data['offset']) ? $data['offset'] : null;
        $this->container['time'] = isset($data['time']) ? $data['time'] : null;
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
    public function getModeAllowableValues()
    {
        return [
            self::MODE_DAILY,
            self::MODE_MONTHLY_FIRST_WORKDAY,
            self::MODE_MONTHLY_LAST_WORKDAY,
            self::MODE_WORKDAYS_ODD,
            self::MODE_WORKDAYS_EVEN,
            self::MODE_WEEKLY,
            self::MODE_MONTHLY_SELECTED,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getOffsetAllowableValues()
    {
        return [
            self::OFFSET_BACKWARD,
            self::OFFSET_FORWARD,
            self::OFFSET_NONE,
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

        if ($this->container['mode'] === null) {
            $invalidProperties[] = "'mode' can't be null";
        }
        $allowedValues = $this->getModeAllowableValues();
        if (!is_null($this->container['mode']) && !in_array($this->container['mode'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'mode', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getOffsetAllowableValues();
        if (!is_null($this->container['offset']) && !in_array($this->container['offset'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'offset', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['time'] === null) {
            $invalidProperties[] = "'time' can't be null";
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
     * Gets daysOfMonth
     *
     * @return string
     */
    public function getDaysOfMonth()
    {
        return $this->container['daysOfMonth'];
    }

    /**
     * Sets daysOfMonth
     *
     * @param string $daysOfMonth Дни месяца, через запятую
     *
     * @return $this
     */
    public function setDaysOfMonth($daysOfMonth)
    {
        $this->container['daysOfMonth'] = $daysOfMonth;

        return $this;
    }

    /**
     * Gets daysOfWeek
     *
     * @return string
     */
    public function getDaysOfWeek()
    {
        return $this->container['daysOfWeek'];
    }

    /**
     * Sets daysOfWeek
     *
     * @param string $daysOfWeek Дни недели, через запятую
     *
     * @return $this
     */
    public function setDaysOfWeek($daysOfWeek)
    {
        $this->container['daysOfWeek'] = $daysOfWeek;

        return $this;
    }

    /**
     * Gets mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->container['mode'];
    }

    /**
     * Sets mode
     *
     * @param string $mode Режим определения периодичности
     *
     * @return $this
     */
    public function setMode($mode)
    {
        $allowedValues = $this->getModeAllowableValues();
        if (!in_array($mode, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'mode', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['mode'] = $mode;

        return $this;
    }

    /**
     * Gets offset
     *
     * @return string
     */
    public function getOffset()
    {
        return $this->container['offset'];
    }

    /**
     * Sets offset
     *
     * @param string $offset Режим смещения платежа в связи с нерабочими днями
     *
     * @return $this
     */
    public function setOffset($offset)
    {
        $allowedValues = $this->getOffsetAllowableValues();
        if (!is_null($offset) && !in_array($offset, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'offset', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['offset'] = $offset;

        return $this;
    }

    /**
     * Gets time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->container['time'];
    }

    /**
     * Sets time
     *
     * @param string $time Время совершения платежа
     *
     * @return $this
     */
    public function setTime($time)
    {
        $this->container['time'] = $time;

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
