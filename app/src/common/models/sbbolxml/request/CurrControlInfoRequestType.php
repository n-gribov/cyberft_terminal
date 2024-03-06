<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrControlInfoRequestType
 *
 *
 * XSD Type: CurrControlInfoRequest
 */
class CurrControlInfoRequestType extends DocBaseType
{

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\sbbolxml\request\CurrControlInfoRequestDocDataType $docData
     */
    private $docData = null;

    /**
     * @property \common\models\sbbolxml\request\VBKInfoType[] $vBKInfos
     */
    private $vBKInfos = null;

    /**
     * Запросить поступившую в банк информацию о зарегистрированных таможенными органами декларациях на товары
     *
     * @property \common\models\sbbolxml\request\PeriodType $customsDeclaration
     */
    private $customsDeclaration = null;

    /**
     * Запросить информацию о зарегистрированных банком нарушениях валютного законодательства
     *
     * @property \common\models\sbbolxml\request\PeriodType $violationCurrencyLaws
     */
    private $violationCurrencyLaws = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты документа
     *
     * @return \common\models\sbbolxml\request\CurrControlInfoRequestDocDataType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа
     *
     * @param \common\models\sbbolxml\request\CurrControlInfoRequestDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\CurrControlInfoRequestDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as vBKInfo
     *
     * @return static
     * @param \common\models\sbbolxml\request\VBKInfoType $vBKInfo
     */
    public function addToVBKInfos(\common\models\sbbolxml\request\VBKInfoType $vBKInfo)
    {
        $this->vBKInfos[] = $vBKInfo;
        return $this;
    }

    /**
     * isset vBKInfos
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetVBKInfos($index)
    {
        return isset($this->vBKInfos[$index]);
    }

    /**
     * unset vBKInfos
     *
     * @param scalar $index
     * @return void
     */
    public function unsetVBKInfos($index)
    {
        unset($this->vBKInfos[$index]);
    }

    /**
     * Gets as vBKInfos
     *
     * @return \common\models\sbbolxml\request\VBKInfoType[]
     */
    public function getVBKInfos()
    {
        return $this->vBKInfos;
    }

    /**
     * Sets a new vBKInfos
     *
     * @param \common\models\sbbolxml\request\VBKInfoType[] $vBKInfos
     * @return static
     */
    public function setVBKInfos(array $vBKInfos)
    {
        $this->vBKInfos = $vBKInfos;
        return $this;
    }

    /**
     * Gets as customsDeclaration
     *
     * Запросить поступившую в банк информацию о зарегистрированных таможенными органами декларациях на товары
     *
     * @return \common\models\sbbolxml\request\PeriodType
     */
    public function getCustomsDeclaration()
    {
        return $this->customsDeclaration;
    }

    /**
     * Sets a new customsDeclaration
     *
     * Запросить поступившую в банк информацию о зарегистрированных таможенными органами декларациях на товары
     *
     * @param \common\models\sbbolxml\request\PeriodType $customsDeclaration
     * @return static
     */
    public function setCustomsDeclaration(\common\models\sbbolxml\request\PeriodType $customsDeclaration)
    {
        $this->customsDeclaration = $customsDeclaration;
        return $this;
    }

    /**
     * Gets as violationCurrencyLaws
     *
     * Запросить информацию о зарегистрированных банком нарушениях валютного законодательства
     *
     * @return \common\models\sbbolxml\request\PeriodType
     */
    public function getViolationCurrencyLaws()
    {
        return $this->violationCurrencyLaws;
    }

    /**
     * Sets a new violationCurrencyLaws
     *
     * Запросить информацию о зарегистрированных банком нарушениях валютного законодательства
     *
     * @param \common\models\sbbolxml\request\PeriodType $violationCurrencyLaws
     * @return static
     */
    public function setViolationCurrencyLaws(\common\models\sbbolxml\request\PeriodType $violationCurrencyLaws)
    {
        $this->violationCurrencyLaws = $violationCurrencyLaws;
        return $this;
    }


}

