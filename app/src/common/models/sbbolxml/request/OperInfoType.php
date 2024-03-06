<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing OperInfoType
 *
 *
 * XSD Type: OperInfo
 */
class OperInfoType
{

    /**
     * Код вида операции
     *
     * @property string $operCode
     */
    private $operCode = null;

    /**
     * Наименование вида валютной операции
     *
     * @property string $operName
     */
    private $operName = null;

    /**
     * Gets as operCode
     *
     * Код вида операции
     *
     * @return string
     */
    public function getOperCode()
    {
        return $this->operCode;
    }

    /**
     * Sets a new operCode
     *
     * Код вида операции
     *
     * @param string $operCode
     * @return static
     */
    public function setOperCode($operCode)
    {
        $this->operCode = $operCode;
        return $this;
    }

    /**
     * Gets as operName
     *
     * Наименование вида валютной операции
     *
     * @return string
     */
    public function getOperName()
    {
        return $this->operName;
    }

    /**
     * Sets a new operName
     *
     * Наименование вида валютной операции
     *
     * @param string $operName
     * @return static
     */
    public function setOperName($operName)
    {
        $this->operName = $operName;
        return $this;
    }


}

