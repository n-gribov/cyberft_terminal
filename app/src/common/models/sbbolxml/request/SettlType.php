<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SettlType
 *
 *
 * XSD Type: Settl
 */
class SettlType
{

    /**
     * Тип. нас. пункта
     *
     * @property string $settlType
     */
    private $settlType = null;

    /**
     * Наименование нас. пункта
     *
     * @property string $settlName
     */
    private $settlName = null;

    /**
     * Gets as settlType
     *
     * Тип. нас. пункта
     *
     * @return string
     */
    public function getSettlType()
    {
        return $this->settlType;
    }

    /**
     * Sets a new settlType
     *
     * Тип. нас. пункта
     *
     * @param string $settlType
     * @return static
     */
    public function setSettlType($settlType)
    {
        $this->settlType = $settlType;
        return $this;
    }

    /**
     * Gets as settlName
     *
     * Наименование нас. пункта
     *
     * @return string
     */
    public function getSettlName()
    {
        return $this->settlName;
    }

    /**
     * Sets a new settlName
     *
     * Наименование нас. пункта
     *
     * @param string $settlName
     * @return static
     */
    public function setSettlName($settlName)
    {
        $this->settlName = $settlName;
        return $this;
    }


}

