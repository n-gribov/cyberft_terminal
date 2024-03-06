<?php

namespace common\models\sbbolxml\response\RZKPayDocCurType;

/**
 * Class representing RzkAType
 */
class RzkAType
{

    /**
     * Параметры обработки документа в РЦК (СБК)
     *
     * @property \common\models\sbbolxml\response\RzkParamsType $rzkParams
     */
    private $rzkParams = null;

    /**
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК (СБК)
     *
     * @property \common\models\sbbolxml\response\RZKPayDocCurType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType[] $rzkDocanalytics
     */
    private $rzkDocanalytics = null;

    /**
     * Gets as rzkParams
     *
     * Параметры обработки документа в РЦК (СБК)
     *
     * @return \common\models\sbbolxml\response\RzkParamsType
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
     * @param \common\models\sbbolxml\response\RzkParamsType $rzkParams
     * @return static
     */
    public function setRzkParams(\common\models\sbbolxml\response\RzkParamsType $rzkParams)
    {
        $this->rzkParams = $rzkParams;
        return $this;
    }

    /**
     * Adds as rzkDocanalytic
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК (СБК)
     *
     * @return static
     * @param \common\models\sbbolxml\response\RZKPayDocCurType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType $rzkDocanalytic
     */
    public function addToRzkDocanalytics(\common\models\sbbolxml\response\RZKPayDocCurType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType $rzkDocanalytic)
    {
        $this->rzkDocanalytics[] = $rzkDocanalytic;
        return $this;
    }

    /**
     * isset rzkDocanalytics
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК (СБК)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetRzkDocanalytics($index)
    {
        return isset($this->rzkDocanalytics[$index]);
    }

    /**
     * unset rzkDocanalytics
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК (СБК)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetRzkDocanalytics($index)
    {
        unset($this->rzkDocanalytics[$index]);
    }

    /**
     * Gets as rzkDocanalytics
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК (СБК)
     *
     * @return \common\models\sbbolxml\response\RZKPayDocCurType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType[]
     */
    public function getRzkDocanalytics()
    {
        return $this->rzkDocanalytics;
    }

    /**
     * Sets a new rzkDocanalytics
     *
     * Многострочная аналитика платежного документа в соответствии со справочниками РЦК (СБК)
     *
     * @param \common\models\sbbolxml\response\RZKPayDocCurType\RzkAType\RzkDocanalyticsAType\RzkDocanalyticAType[] $rzkDocanalytics
     * @return static
     */
    public function setRzkDocanalytics(array $rzkDocanalytics)
    {
        $this->rzkDocanalytics = $rzkDocanalytics;
        return $this;
    }


}

