<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing OrgUPGLogsRequestType
 *
 * Запрос логов с ТК
 * XSD Type: OrgUPGLogsRequestType
 */
class OrgUPGLogsRequestType
{

    /**
     * Начало периода, за который запращиваются логи с ТК
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Окончание периода, за который запращиваются логи с ТК
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Gets as beginDate
     *
     * Начало периода, за который запращиваются логи с ТК
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Sets a new beginDate
     *
     * Начало периода, за который запращиваются логи с ТК
     *
     * @param \DateTime $beginDate
     * @return static
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Окончание периода, за который запращиваются логи с ТК
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Окончание периода, за который запращиваются логи с ТК
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }


}

