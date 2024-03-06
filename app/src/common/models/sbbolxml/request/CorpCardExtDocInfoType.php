<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorpCardExtDocInfoType
 *
 * Информация по документам держателя КК
 * XSD Type: CorpCardExtDocInfo
 */
class CorpCardExtDocInfoType
{

    /**
     * Тип удостоверения
     *
     * @property string $docKind
     */
    private $docKind = null;

    /**
     * Серия удостоверения
     *
     * @property string $docSeries
     */
    private $docSeries = null;

    /**
     * Номер удостоверения
     *
     * @property string $docNumb
     */
    private $docNumb = null;

    /**
     * Дата выдачи
     *
     * @property \DateTime $docIssueDt
     */
    private $docIssueDt = null;

    /**
     * Место выдачи
     *
     * @property string $docIssuePlace
     */
    private $docIssuePlace = null;

    /**
     * Код подразделения
     *
     * @property string $docIssueCode
     */
    private $docIssueCode = null;

    /**
     * Gets as docKind
     *
     * Тип удостоверения
     *
     * @return string
     */
    public function getDocKind()
    {
        return $this->docKind;
    }

    /**
     * Sets a new docKind
     *
     * Тип удостоверения
     *
     * @param string $docKind
     * @return static
     */
    public function setDocKind($docKind)
    {
        $this->docKind = $docKind;
        return $this;
    }

    /**
     * Gets as docSeries
     *
     * Серия удостоверения
     *
     * @return string
     */
    public function getDocSeries()
    {
        return $this->docSeries;
    }

    /**
     * Sets a new docSeries
     *
     * Серия удостоверения
     *
     * @param string $docSeries
     * @return static
     */
    public function setDocSeries($docSeries)
    {
        $this->docSeries = $docSeries;
        return $this;
    }

    /**
     * Gets as docNumb
     *
     * Номер удостоверения
     *
     * @return string
     */
    public function getDocNumb()
    {
        return $this->docNumb;
    }

    /**
     * Sets a new docNumb
     *
     * Номер удостоверения
     *
     * @param string $docNumb
     * @return static
     */
    public function setDocNumb($docNumb)
    {
        $this->docNumb = $docNumb;
        return $this;
    }

    /**
     * Gets as docIssueDt
     *
     * Дата выдачи
     *
     * @return \DateTime
     */
    public function getDocIssueDt()
    {
        return $this->docIssueDt;
    }

    /**
     * Sets a new docIssueDt
     *
     * Дата выдачи
     *
     * @param \DateTime $docIssueDt
     * @return static
     */
    public function setDocIssueDt(\DateTime $docIssueDt)
    {
        $this->docIssueDt = $docIssueDt;
        return $this;
    }

    /**
     * Gets as docIssuePlace
     *
     * Место выдачи
     *
     * @return string
     */
    public function getDocIssuePlace()
    {
        return $this->docIssuePlace;
    }

    /**
     * Sets a new docIssuePlace
     *
     * Место выдачи
     *
     * @param string $docIssuePlace
     * @return static
     */
    public function setDocIssuePlace($docIssuePlace)
    {
        $this->docIssuePlace = $docIssuePlace;
        return $this;
    }

    /**
     * Gets as docIssueCode
     *
     * Код подразделения
     *
     * @return string
     */
    public function getDocIssueCode()
    {
        return $this->docIssueCode;
    }

    /**
     * Sets a new docIssueCode
     *
     * Код подразделения
     *
     * @param string $docIssueCode
     * @return static
     */
    public function setDocIssueCode($docIssueCode)
    {
        $this->docIssueCode = $docIssueCode;
        return $this;
    }


}

