<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing FeesRegistriesType
 *
 * Реестры платежей
 * XSD Type: FeesRegistries
 */
class FeesRegistriesType
{

    /**
     * Реестр платежей
     *
     * @property \common\models\sbbolxml\response\FeesRegistriesType\FeesRegistryAType[] $feesRegistry
     */
    private $feesRegistry = array(
        
    );

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Adds as feesRegistry
     *
     * Реестр платежей
     *
     * @return static
     * @param \common\models\sbbolxml\response\FeesRegistriesType\FeesRegistryAType $feesRegistry
     */
    public function addToFeesRegistry(\common\models\sbbolxml\response\FeesRegistriesType\FeesRegistryAType $feesRegistry)
    {
        $this->feesRegistry[] = $feesRegistry;
        return $this;
    }

    /**
     * isset feesRegistry
     *
     * Реестр платежей
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFeesRegistry($index)
    {
        return isset($this->feesRegistry[$index]);
    }

    /**
     * unset feesRegistry
     *
     * Реестр платежей
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFeesRegistry($index)
    {
        unset($this->feesRegistry[$index]);
    }

    /**
     * Gets as feesRegistry
     *
     * Реестр платежей
     *
     * @return \common\models\sbbolxml\response\FeesRegistriesType\FeesRegistryAType[]
     */
    public function getFeesRegistry()
    {
        return $this->feesRegistry;
    }

    /**
     * Sets a new feesRegistry
     *
     * Реестр платежей
     *
     * @param \common\models\sbbolxml\response\FeesRegistriesType\FeesRegistryAType[] $feesRegistry
     * @return static
     */
    public function setFeesRegistry(array $feesRegistry)
    {
        $this->feesRegistry = $feesRegistry;
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

