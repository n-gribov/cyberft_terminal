<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing GozExecutorType
 *
 * Исполнитель
 * XSD Type: GozExecutor
 */
class GozExecutorType
{

    /**
     * ИНН исполнителя
     *
     * @property string $extINN
     */
    private $extINN = null;

    /**
     * КПП исполнителя
     *
     * @property string $extKPP
     */
    private $extKPP = null;

    /**
     * № отдельного счета исполнителя
     *
     * @property string $extAcc
     */
    private $extAcc = null;

    /**
     * Наименование исполнителя
     *
     * @property string $extName
     */
    private $extName = null;

    /**
     * 1 - резидент, 0 - нерезидент
     *
     * @property boolean $resident
     */
    private $resident = null;

    /**
     * Gets as extINN
     *
     * ИНН исполнителя
     *
     * @return string
     */
    public function getExtINN()
    {
        return $this->extINN;
    }

    /**
     * Sets a new extINN
     *
     * ИНН исполнителя
     *
     * @param string $extINN
     * @return static
     */
    public function setExtINN($extINN)
    {
        $this->extINN = $extINN;
        return $this;
    }

    /**
     * Gets as extKPP
     *
     * КПП исполнителя
     *
     * @return string
     */
    public function getExtKPP()
    {
        return $this->extKPP;
    }

    /**
     * Sets a new extKPP
     *
     * КПП исполнителя
     *
     * @param string $extKPP
     * @return static
     */
    public function setExtKPP($extKPP)
    {
        $this->extKPP = $extKPP;
        return $this;
    }

    /**
     * Gets as extAcc
     *
     * № отдельного счета исполнителя
     *
     * @return string
     */
    public function getExtAcc()
    {
        return $this->extAcc;
    }

    /**
     * Sets a new extAcc
     *
     * № отдельного счета исполнителя
     *
     * @param string $extAcc
     * @return static
     */
    public function setExtAcc($extAcc)
    {
        $this->extAcc = $extAcc;
        return $this;
    }

    /**
     * Gets as extName
     *
     * Наименование исполнителя
     *
     * @return string
     */
    public function getExtName()
    {
        return $this->extName;
    }

    /**
     * Sets a new extName
     *
     * Наименование исполнителя
     *
     * @param string $extName
     * @return static
     */
    public function setExtName($extName)
    {
        $this->extName = $extName;
        return $this;
    }

    /**
     * Gets as resident
     *
     * 1 - резидент, 0 - нерезидент
     *
     * @return boolean
     */
    public function getResident()
    {
        return $this->resident;
    }

    /**
     * Sets a new resident
     *
     * 1 - резидент, 0 - нерезидент
     *
     * @param boolean $resident
     * @return static
     */
    public function setResident($resident)
    {
        $this->resident = $resident;
        return $this;
    }


}

