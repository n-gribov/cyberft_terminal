<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing SIType
 *
 * ПИ
 * XSD Type: SIType
 */
class SIType
{

    /**
     * ПИ для всей сделки или для первой ноги
     *
     * @property \common\models\sbbolxml\response\SIInfoType[] $sIInfos
     */
    private $sIInfos = null;

    /**
     * ПИ по второй ноге сделки
     *
     * @property \common\models\sbbolxml\response\SIInfoType[] $sIInfos2
     */
    private $sIInfos2 = null;

    /**
     * Adds as sIInfo
     *
     * ПИ для всей сделки или для первой ноги
     *
     * @return static
     * @param \common\models\sbbolxml\response\SIInfoType $sIInfo
     */
    public function addToSIInfos(\common\models\sbbolxml\response\SIInfoType $sIInfo)
    {
        $this->sIInfos[] = $sIInfo;
        return $this;
    }

    /**
     * isset sIInfos
     *
     * ПИ для всей сделки или для первой ноги
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSIInfos($index)
    {
        return isset($this->sIInfos[$index]);
    }

    /**
     * unset sIInfos
     *
     * ПИ для всей сделки или для первой ноги
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSIInfos($index)
    {
        unset($this->sIInfos[$index]);
    }

    /**
     * Gets as sIInfos
     *
     * ПИ для всей сделки или для первой ноги
     *
     * @return \common\models\sbbolxml\response\SIInfoType[]
     */
    public function getSIInfos()
    {
        return $this->sIInfos;
    }

    /**
     * Sets a new sIInfos
     *
     * ПИ для всей сделки или для первой ноги
     *
     * @param \common\models\sbbolxml\response\SIInfoType[] $sIInfos
     * @return static
     */
    public function setSIInfos(array $sIInfos)
    {
        $this->sIInfos = $sIInfos;
        return $this;
    }

    /**
     * Adds as sIInfo
     *
     * ПИ по второй ноге сделки
     *
     * @return static
     * @param \common\models\sbbolxml\response\SIInfoType $sIInfo
     */
    public function addToSIInfos2(\common\models\sbbolxml\response\SIInfoType $sIInfo)
    {
        $this->sIInfos2[] = $sIInfo;
        return $this;
    }

    /**
     * isset sIInfos2
     *
     * ПИ по второй ноге сделки
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSIInfos2($index)
    {
        return isset($this->sIInfos2[$index]);
    }

    /**
     * unset sIInfos2
     *
     * ПИ по второй ноге сделки
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSIInfos2($index)
    {
        unset($this->sIInfos2[$index]);
    }

    /**
     * Gets as sIInfos2
     *
     * ПИ по второй ноге сделки
     *
     * @return \common\models\sbbolxml\response\SIInfoType[]
     */
    public function getSIInfos2()
    {
        return $this->sIInfos2;
    }

    /**
     * Sets a new sIInfos2
     *
     * ПИ по второй ноге сделки
     *
     * @param \common\models\sbbolxml\response\SIInfoType[] $sIInfos2
     * @return static
     */
    public function setSIInfos2(array $sIInfos2)
    {
        $this->sIInfos2 = $sIInfos2;
        return $this;
    }


}

