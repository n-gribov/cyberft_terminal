<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CurrControlSettingsType
 *
 * Настройки валютного контроля
 * XSD Type: CurrControlSettings
 */
class CurrControlSettingsType
{

    /**
     * Запросить информацию из ВБК, 0 - не установлен, 1 - установлен
     *
     * @property boolean $vbkInfo
     */
    private $vbkInfo = null;

    /**
     * Запросить информацию из ВБК (полностью): 0 - не установлен, 1 - установлен
     *
     * @property boolean $vbkInfoFull
     */
    private $vbkInfoFull = null;

    /**
     * Запрос деклораций на товары, 0 - не установлен, 1 - установлен
     *
     * @property boolean $cdInfo
     */
    private $cdInfo = null;

    /**
     * Запрос информации о нарушениях валютного законодательств, 0 - не установлен, 1 -
     *  установлен
     *
     * @property boolean $violationCurrLaw
     */
    private $violationCurrLaw = null;

    /**
     * Gets as vbkInfo
     *
     * Запросить информацию из ВБК, 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getVbkInfo()
    {
        return $this->vbkInfo;
    }

    /**
     * Sets a new vbkInfo
     *
     * Запросить информацию из ВБК, 0 - не установлен, 1 - установлен
     *
     * @param boolean $vbkInfo
     * @return static
     */
    public function setVbkInfo($vbkInfo)
    {
        $this->vbkInfo = $vbkInfo;
        return $this;
    }

    /**
     * Gets as vbkInfoFull
     *
     * Запросить информацию из ВБК (полностью): 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getVbkInfoFull()
    {
        return $this->vbkInfoFull;
    }

    /**
     * Sets a new vbkInfoFull
     *
     * Запросить информацию из ВБК (полностью): 0 - не установлен, 1 - установлен
     *
     * @param boolean $vbkInfoFull
     * @return static
     */
    public function setVbkInfoFull($vbkInfoFull)
    {
        $this->vbkInfoFull = $vbkInfoFull;
        return $this;
    }

    /**
     * Gets as cdInfo
     *
     * Запрос деклораций на товары, 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getCdInfo()
    {
        return $this->cdInfo;
    }

    /**
     * Sets a new cdInfo
     *
     * Запрос деклораций на товары, 0 - не установлен, 1 - установлен
     *
     * @param boolean $cdInfo
     * @return static
     */
    public function setCdInfo($cdInfo)
    {
        $this->cdInfo = $cdInfo;
        return $this;
    }

    /**
     * Gets as violationCurrLaw
     *
     * Запрос информации о нарушениях валютного законодательств, 0 - не установлен, 1 -
     *  установлен
     *
     * @return boolean
     */
    public function getViolationCurrLaw()
    {
        return $this->violationCurrLaw;
    }

    /**
     * Sets a new violationCurrLaw
     *
     * Запрос информации о нарушениях валютного законодательств, 0 - не установлен, 1 -
     *  установлен
     *
     * @param boolean $violationCurrLaw
     * @return static
     */
    public function setViolationCurrLaw($violationCurrLaw)
    {
        $this->violationCurrLaw = $violationCurrLaw;
        return $this;
    }


}

