<?php

namespace common\models\sbbolxml\request\RightToStayDocumentType;

/**
 * Class representing OtherRightToStayDocumentAType
 */
class OtherRightToStayDocumentAType
{

    /**
     * Наименование документа
     *
     * @property string $rightToStayDocumentName
     */
    private $rightToStayDocumentName = null;

    /**
     * Номер/серия
     *
     * @property string $rightToStayDocumentSeriesOrNumber
     */
    private $rightToStayDocumentSeriesOrNumber = null;

    /**
     * Дата начала срока пребывания
     *
     * @property \DateTime $durationOfStayBeginDate
     */
    private $durationOfStayBeginDate = null;

    /**
     * Дата окончания срока пребывания
     *
     * @property \DateTime $durationOfStayEndDate
     */
    private $durationOfStayEndDate = null;

    /**
     * Gets as rightToStayDocumentName
     *
     * Наименование документа
     *
     * @return string
     */
    public function getRightToStayDocumentName()
    {
        return $this->rightToStayDocumentName;
    }

    /**
     * Sets a new rightToStayDocumentName
     *
     * Наименование документа
     *
     * @param string $rightToStayDocumentName
     * @return static
     */
    public function setRightToStayDocumentName($rightToStayDocumentName)
    {
        $this->rightToStayDocumentName = $rightToStayDocumentName;
        return $this;
    }

    /**
     * Gets as rightToStayDocumentSeriesOrNumber
     *
     * Номер/серия
     *
     * @return string
     */
    public function getRightToStayDocumentSeriesOrNumber()
    {
        return $this->rightToStayDocumentSeriesOrNumber;
    }

    /**
     * Sets a new rightToStayDocumentSeriesOrNumber
     *
     * Номер/серия
     *
     * @param string $rightToStayDocumentSeriesOrNumber
     * @return static
     */
    public function setRightToStayDocumentSeriesOrNumber($rightToStayDocumentSeriesOrNumber)
    {
        $this->rightToStayDocumentSeriesOrNumber = $rightToStayDocumentSeriesOrNumber;
        return $this;
    }

    /**
     * Gets as durationOfStayBeginDate
     *
     * Дата начала срока пребывания
     *
     * @return \DateTime
     */
    public function getDurationOfStayBeginDate()
    {
        return $this->durationOfStayBeginDate;
    }

    /**
     * Sets a new durationOfStayBeginDate
     *
     * Дата начала срока пребывания
     *
     * @param \DateTime $durationOfStayBeginDate
     * @return static
     */
    public function setDurationOfStayBeginDate(\DateTime $durationOfStayBeginDate)
    {
        $this->durationOfStayBeginDate = $durationOfStayBeginDate;
        return $this;
    }

    /**
     * Gets as durationOfStayEndDate
     *
     * Дата окончания срока пребывания
     *
     * @return \DateTime
     */
    public function getDurationOfStayEndDate()
    {
        return $this->durationOfStayEndDate;
    }

    /**
     * Sets a new durationOfStayEndDate
     *
     * Дата окончания срока пребывания
     *
     * @param \DateTime $durationOfStayEndDate
     * @return static
     */
    public function setDurationOfStayEndDate(\DateTime $durationOfStayEndDate)
    {
        $this->durationOfStayEndDate = $durationOfStayEndDate;
        return $this;
    }


}

