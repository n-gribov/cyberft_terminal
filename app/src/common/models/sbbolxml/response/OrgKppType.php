<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing OrgKppType
 *
 *
 * XSD Type: OrgKpp
 */
class OrgKppType
{

    /**
     * КПП
     *
     * @property string $kPPIndex
     */
    private $kPPIndex = null;

    /**
     * Признак, использовать ли КПП по умолчанию, заполняем значением "1" только в том
     *  случае, если для данного КПП стоит такой признак. Иначе- не передаем.
     *
     * @property string $checkKPP
     */
    private $checkKPP = null;

    /**
     * Gets as kPPIndex
     *
     * КПП
     *
     * @return string
     */
    public function getKPPIndex()
    {
        return $this->kPPIndex;
    }

    /**
     * Sets a new kPPIndex
     *
     * КПП
     *
     * @param string $kPPIndex
     * @return static
     */
    public function setKPPIndex($kPPIndex)
    {
        $this->kPPIndex = $kPPIndex;
        return $this;
    }

    /**
     * Gets as checkKPP
     *
     * Признак, использовать ли КПП по умолчанию, заполняем значением "1" только в том
     *  случае, если для данного КПП стоит такой признак. Иначе- не передаем.
     *
     * @return string
     */
    public function getCheckKPP()
    {
        return $this->checkKPP;
    }

    /**
     * Sets a new checkKPP
     *
     * Признак, использовать ли КПП по умолчанию, заполняем значением "1" только в том
     *  случае, если для данного КПП стоит такой признак. Иначе- не передаем.
     *
     * @param string $checkKPP
     * @return static
     */
    public function setCheckKPP($checkKPP)
    {
        $this->checkKPP = $checkKPP;
        return $this;
    }


}

