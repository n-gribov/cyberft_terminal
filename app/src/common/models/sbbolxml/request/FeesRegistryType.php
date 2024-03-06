<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FeesRegistryType
 *
 * Реестр задолженностей
 * XSD Type: FeesRegistry
 */
class FeesRegistryType
{

    /**
     * Реквизиты реестра задолженностей
     *
     * @property \common\models\sbbolxml\request\FeesRegistryType\FeesRegistryDataAType $feesRegistryData
     */
    private $feesRegistryData = null;

    /**
     * Gets as feesRegistryData
     *
     * Реквизиты реестра задолженностей
     *
     * @return \common\models\sbbolxml\request\FeesRegistryType\FeesRegistryDataAType
     */
    public function getFeesRegistryData()
    {
        return $this->feesRegistryData;
    }

    /**
     * Sets a new feesRegistryData
     *
     * Реквизиты реестра задолженностей
     *
     * @param \common\models\sbbolxml\request\FeesRegistryType\FeesRegistryDataAType $feesRegistryData
     * @return static
     */
    public function setFeesRegistryData(\common\models\sbbolxml\request\FeesRegistryType\FeesRegistryDataAType $feesRegistryData)
    {
        $this->feesRegistryData = $feesRegistryData;
        return $this;
    }


}

