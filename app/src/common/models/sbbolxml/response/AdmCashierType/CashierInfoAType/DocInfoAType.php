<?php

namespace common\models\sbbolxml\response\AdmCashierType\CashierInfoAType;

/**
 * Class representing DocInfoAType
 */
class DocInfoAType
{

    /**
     * Вид документа, содержит следующие значения:
     *  PassportOfRussia - Паспорт гражданина РФ
     *  ForeignPassport- Паспорт иностранного гражданина
     *  OtherKindOfDocument - Другой вид документа.
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Наименование другого документа
     *
     * @property string $docName
     */
    private $docName = null;

    /**
     * Номер документа
     *
     * @property string $docNumb
     */
    private $docNumb = null;

    /**
     * Серия документа
     *
     * @property string $docSeries
     */
    private $docSeries = null;

    /**
     * Дата выдачи
     *
     * @property \DateTime $docIssueDt
     */
    private $docIssueDt = null;

    /**
     * Кем выдан
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
     * Цифровой Код в соответствии с Общероссийским классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @property string $citizenship
     */
    private $citizenship = null;

    /**
     * Наменование страны гражданства вносителя.
     *
     * @property string $citizenshipCountryName
     */
    private $citizenshipCountryName = null;

    /**
     * Gets as docType
     *
     * Вид документа, содержит следующие значения:
     *  PassportOfRussia - Паспорт гражданина РФ
     *  ForeignPassport- Паспорт иностранного гражданина
     *  OtherKindOfDocument - Другой вид документа.
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
     * Вид документа, содержит следующие значения:
     *  PassportOfRussia - Паспорт гражданина РФ
     *  ForeignPassport- Паспорт иностранного гражданина
     *  OtherKindOfDocument - Другой вид документа.
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
     * Gets as docName
     *
     * Наименование другого документа
     *
     * @return string
     */
    public function getDocName()
    {
        return $this->docName;
    }

    /**
     * Sets a new docName
     *
     * Наименование другого документа
     *
     * @param string $docName
     * @return static
     */
    public function setDocName($docName)
    {
        $this->docName = $docName;
        return $this;
    }

    /**
     * Gets as docNumb
     *
     * Номер документа
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
     * Номер документа
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
     * Gets as docSeries
     *
     * Серия документа
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
     * Серия документа
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
     * Кем выдан
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
     * Кем выдан
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

    /**
     * Gets as citizenship
     *
     * Цифровой Код в соответствии с Общероссийским классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @return string
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Sets a new citizenship
     *
     * Цифровой Код в соответствии с Общероссийским классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @param string $citizenship
     * @return static
     */
    public function setCitizenship($citizenship)
    {
        $this->citizenship = $citizenship;
        return $this;
    }

    /**
     * Gets as citizenshipCountryName
     *
     * Наменование страны гражданства вносителя.
     *
     * @return string
     */
    public function getCitizenshipCountryName()
    {
        return $this->citizenshipCountryName;
    }

    /**
     * Sets a new citizenshipCountryName
     *
     * Наменование страны гражданства вносителя.
     *
     * @param string $citizenshipCountryName
     * @return static
     */
    public function setCitizenshipCountryName($citizenshipCountryName)
    {
        $this->citizenshipCountryName = $citizenshipCountryName;
        return $this;
    }


}

