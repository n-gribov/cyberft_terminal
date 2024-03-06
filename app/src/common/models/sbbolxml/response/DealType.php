<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DealType
 *
 * Информация по сделке
 * XSD Type: DealType
 */
class DealType
{

    /**
     * Информация о контрагенте
     *
     * @property \common\models\sbbolxml\response\CptyType $cpty
     */
    private $cpty = null;

    /**
     * Информация о сделке
     *
     * @property \common\models\sbbolxml\response\DealInfoType $dealInfo
     */
    private $dealInfo = null;

    /**
     * Информация о ногах сделки
     *
     * @property \common\models\sbbolxml\response\DealLegsInfoType $dealLegs
     */
    private $dealLegs = null;

    /**
     * Gets as cpty
     *
     * Информация о контрагенте
     *
     * @return \common\models\sbbolxml\response\CptyType
     */
    public function getCpty()
    {
        return $this->cpty;
    }

    /**
     * Sets a new cpty
     *
     * Информация о контрагенте
     *
     * @param \common\models\sbbolxml\response\CptyType $cpty
     * @return static
     */
    public function setCpty(\common\models\sbbolxml\response\CptyType $cpty)
    {
        $this->cpty = $cpty;
        return $this;
    }

    /**
     * Gets as dealInfo
     *
     * Информация о сделке
     *
     * @return \common\models\sbbolxml\response\DealInfoType
     */
    public function getDealInfo()
    {
        return $this->dealInfo;
    }

    /**
     * Sets a new dealInfo
     *
     * Информация о сделке
     *
     * @param \common\models\sbbolxml\response\DealInfoType $dealInfo
     * @return static
     */
    public function setDealInfo(\common\models\sbbolxml\response\DealInfoType $dealInfo)
    {
        $this->dealInfo = $dealInfo;
        return $this;
    }

    /**
     * Gets as dealLegs
     *
     * Информация о ногах сделки
     *
     * @return \common\models\sbbolxml\response\DealLegsInfoType
     */
    public function getDealLegs()
    {
        return $this->dealLegs;
    }

    /**
     * Sets a new dealLegs
     *
     * Информация о ногах сделки
     *
     * @param \common\models\sbbolxml\response\DealLegsInfoType $dealLegs
     * @return static
     */
    public function setDealLegs(\common\models\sbbolxml\response\DealLegsInfoType $dealLegs)
    {
        $this->dealLegs = $dealLegs;
        return $this;
    }


}

