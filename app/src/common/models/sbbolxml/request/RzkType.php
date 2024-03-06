<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RzkType
 *
 * Многострочная аналитика и параметры обработки платежного документа в РЦК (СБК)
 * XSD Type: Rzk
 */
class RzkType
{

    /**
     * Параметры обработки документа в РЦК (СБК)
     *
     * @property \common\models\sbbolxml\request\RzkParamsType $rzkParams
     */
    private $rzkParams = null;

    /**
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК
     *  (СБК)
     *
     * @property \common\models\sbbolxml\request\RzkDocAnalyticType[] $rzkDocAnalytics
     */
    private $rzkDocAnalytics = null;

    /**
     * Gets as rzkParams
     *
     * Параметры обработки документа в РЦК (СБК)
     *
     * @return \common\models\sbbolxml\request\RzkParamsType
     */
    public function getRzkParams()
    {
        return $this->rzkParams;
    }

    /**
     * Sets a new rzkParams
     *
     * Параметры обработки документа в РЦК (СБК)
     *
     * @param \common\models\sbbolxml\request\RzkParamsType $rzkParams
     * @return static
     */
    public function setRzkParams(\common\models\sbbolxml\request\RzkParamsType $rzkParams)
    {
        $this->rzkParams = $rzkParams;
        return $this;
    }

    /**
     * Adds as rzkDocAnalytic
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК
     *  (СБК)
     *
     * @return static
     * @param \common\models\sbbolxml\request\RzkDocAnalyticType $rzkDocAnalytic
     */
    public function addToRzkDocAnalytics(\common\models\sbbolxml\request\RzkDocAnalyticType $rzkDocAnalytic)
    {
        $this->rzkDocAnalytics[] = $rzkDocAnalytic;
        return $this;
    }

    /**
     * isset rzkDocAnalytics
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК
     *  (СБК)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetRzkDocAnalytics($index)
    {
        return isset($this->rzkDocAnalytics[$index]);
    }

    /**
     * unset rzkDocAnalytics
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК
     *  (СБК)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetRzkDocAnalytics($index)
    {
        unset($this->rzkDocAnalytics[$index]);
    }

    /**
     * Gets as rzkDocAnalytics
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК
     *  (СБК)
     *
     * @return \common\models\sbbolxml\request\RzkDocAnalyticType[]
     */
    public function getRzkDocAnalytics()
    {
        return $this->rzkDocAnalytics;
    }

    /**
     * Sets a new rzkDocAnalytics
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК
     *  (СБК)
     *
     * @param \common\models\sbbolxml\request\RzkDocAnalyticType[] $rzkDocAnalytics
     * @return static
     */
    public function setRzkDocAnalytics(array $rzkDocAnalytics)
    {
        $this->rzkDocAnalytics = $rzkDocAnalytics;
        return $this;
    }


}

