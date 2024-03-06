<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CurrControlInfoRequestType
 *
 * Запрос информации ВК
 * XSD Type: CurrControlInfoRequest
 */
class CurrControlInfoRequestType
{

    /**
     * Информация из ведомости банковского контроля
     *
     * @property \common\models\sbbolxml\response\VBKInfoType[] $vBKInfos
     */
    private $vBKInfos = null;

    /**
     * Информация о декларациях на товары
     *
     * @property string $cDInfo
     */
    private $cDInfo = null;

    /**
     * Информация о зафиксированных банком нарушениях валютного законодательства
     *
     * @property \common\models\sbbolxml\response\ViolationCurrLawType[] $violationCurrLaws
     */
    private $violationCurrLaws = null;

    /**
     * Adds as vBKInfo
     *
     * Информация из ведомости банковского контроля
     *
     * @return static
     * @param \common\models\sbbolxml\response\VBKInfoType $vBKInfo
     */
    public function addToVBKInfos(\common\models\sbbolxml\response\VBKInfoType $vBKInfo)
    {
        $this->vBKInfos[] = $vBKInfo;
        return $this;
    }

    /**
     * isset vBKInfos
     *
     * Информация из ведомости банковского контроля
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
     * Информация из ведомости банковского контроля
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
     * Информация из ведомости банковского контроля
     *
     * @return \common\models\sbbolxml\response\VBKInfoType[]
     */
    public function getVBKInfos()
    {
        return $this->vBKInfos;
    }

    /**
     * Sets a new vBKInfos
     *
     * Информация из ведомости банковского контроля
     *
     * @param \common\models\sbbolxml\response\VBKInfoType[] $vBKInfos
     * @return static
     */
    public function setVBKInfos(array $vBKInfos)
    {
        $this->vBKInfos = $vBKInfos;
        return $this;
    }

    /**
     * Gets as cDInfo
     *
     * Информация о декларациях на товары
     *
     * @return string
     */
    public function getCDInfo()
    {
        return $this->cDInfo;
    }

    /**
     * Sets a new cDInfo
     *
     * Информация о декларациях на товары
     *
     * @param string $cDInfo
     * @return static
     */
    public function setCDInfo($cDInfo)
    {
        $this->cDInfo = $cDInfo;
        return $this;
    }

    /**
     * Adds as violationCurrLaw
     *
     * Информация о зафиксированных банком нарушениях валютного законодательства
     *
     * @return static
     * @param \common\models\sbbolxml\response\ViolationCurrLawType $violationCurrLaw
     */
    public function addToViolationCurrLaws(\common\models\sbbolxml\response\ViolationCurrLawType $violationCurrLaw)
    {
        $this->violationCurrLaws[] = $violationCurrLaw;
        return $this;
    }

    /**
     * isset violationCurrLaws
     *
     * Информация о зафиксированных банком нарушениях валютного законодательства
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetViolationCurrLaws($index)
    {
        return isset($this->violationCurrLaws[$index]);
    }

    /**
     * unset violationCurrLaws
     *
     * Информация о зафиксированных банком нарушениях валютного законодательства
     *
     * @param scalar $index
     * @return void
     */
    public function unsetViolationCurrLaws($index)
    {
        unset($this->violationCurrLaws[$index]);
    }

    /**
     * Gets as violationCurrLaws
     *
     * Информация о зафиксированных банком нарушениях валютного законодательства
     *
     * @return \common\models\sbbolxml\response\ViolationCurrLawType[]
     */
    public function getViolationCurrLaws()
    {
        return $this->violationCurrLaws;
    }

    /**
     * Sets a new violationCurrLaws
     *
     * Информация о зафиксированных банком нарушениях валютного законодательства
     *
     * @param \common\models\sbbolxml\response\ViolationCurrLawType[] $violationCurrLaws
     * @return static
     */
    public function setViolationCurrLaws(array $violationCurrLaws)
    {
        $this->violationCurrLaws = $violationCurrLaws;
        return $this;
    }


}

