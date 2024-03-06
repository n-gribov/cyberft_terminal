<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DocsAType;

/**
 * Class representing DocAType
 */
class DocAType
{

    /**
     * Тип документа
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Сумма
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Срок полномочий
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DocsAType\DocAType\DurationAType $duration
     */
    private $duration = null;

    /**
     * Gets as docType
     *
     * Тип документа
     *
     * @return string
     */
    public function getDocType()
    {
        return $this->docType;
    }

    /**
     * Sets a new docType
     *
     * Тип документа
     *
     * @param string $docType
     * @return static
     */
    public function setDocType($docType)
    {
        $this->docType = $docType;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as duration
     *
     * Срок полномочий
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DocsAType\DocAType\DurationAType
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Sets a new duration
     *
     * Срок полномочий
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DocsAType\DocAType\DurationAType $duration
     * @return static
     */
    public function setDuration(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DocsAType\DocAType\DurationAType $duration)
    {
        $this->duration = $duration;
        return $this;
    }


}

