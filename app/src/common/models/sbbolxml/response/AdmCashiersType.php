<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AdmCashiersType
 *
 * Шаблоны внесения средств
 * XSD Type: AdmCashiers
 */
class AdmCashiersType
{

    /**
     * Идентификатор последнего обновления справочника
     *
     * @property integer $stepId
     */
    private $stepId = null;

    /**
     * @property \common\models\sbbolxml\response\AdmCashierType[] $admCashier
     */
    private $admCashier = array(
        
    );

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as stepId
     *
     * Идентификатор последнего обновления справочника
     *
     * @return integer
     */
    public function getStepId()
    {
        return $this->stepId;
    }

    /**
     * Sets a new stepId
     *
     * Идентификатор последнего обновления справочника
     *
     * @param integer $stepId
     * @return static
     */
    public function setStepId($stepId)
    {
        $this->stepId = $stepId;
        return $this;
    }

    /**
     * Adds as admCashier
     *
     * @return static
     * @param \common\models\sbbolxml\response\AdmCashierType $admCashier
     */
    public function addToAdmCashier(\common\models\sbbolxml\response\AdmCashierType $admCashier)
    {
        $this->admCashier[] = $admCashier;
        return $this;
    }

    /**
     * isset admCashier
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAdmCashier($index)
    {
        return isset($this->admCashier[$index]);
    }

    /**
     * unset admCashier
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAdmCashier($index)
    {
        unset($this->admCashier[$index]);
    }

    /**
     * Gets as admCashier
     *
     * @return \common\models\sbbolxml\response\AdmCashierType[]
     */
    public function getAdmCashier()
    {
        return $this->admCashier;
    }

    /**
     * Sets a new admCashier
     *
     * @param \common\models\sbbolxml\response\AdmCashierType[] $admCashier
     * @return static
     */
    public function setAdmCashier(array $admCashier)
    {
        $this->admCashier = $admCashier;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

