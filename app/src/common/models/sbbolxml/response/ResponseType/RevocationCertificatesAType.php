<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing RevocationCertificatesAType
 */
class RevocationCertificatesAType
{

    /**
     * Дата и время последнего обновления списка отозванных сертификатов
     *
     * @property \DateTime $updDate
     */
    private $updDate = null;

    /**
     * Дата и время начала периода актуальности списка отозванных сертификатов
     *
     * @property \DateTime $startDate
     */
    private $startDate = null;

    /**
     * Дата и время окончания периода актуальности списка отозванных сертификатов
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Ссылка на скачивание файла со списком отозв. сертификатов
     *
     * @property string $postFix
     */
    private $postFix = null;

    /**
     * Код ошибки, при выполнении обновления списка отозванных сертификатов.
     *  Возможные значения: DICT_IS_ACTUAL, FAIL
     *
     * @property string $errorCode
     */
    private $errorCode = null;

    /**
     * Сообщение ошибки, при выполнении обновления списка отозванных сертификатов
     *
     * @property string $errorMsg
     */
    private $errorMsg = null;

    /**
     * Gets as updDate
     *
     * Дата и время последнего обновления списка отозванных сертификатов
     *
     * @return \DateTime
     */
    public function getUpdDate()
    {
        return $this->updDate;
    }

    /**
     * Sets a new updDate
     *
     * Дата и время последнего обновления списка отозванных сертификатов
     *
     * @param \DateTime $updDate
     * @return static
     */
    public function setUpdDate(\DateTime $updDate)
    {
        $this->updDate = $updDate;
        return $this;
    }

    /**
     * Gets as startDate
     *
     * Дата и время начала периода актуальности списка отозванных сертификатов
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Sets a new startDate
     *
     * Дата и время начала периода актуальности списка отозванных сертификатов
     *
     * @param \DateTime $startDate
     * @return static
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата и время окончания периода актуальности списка отозванных сертификатов
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
     * Дата и время окончания периода актуальности списка отозванных сертификатов
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Gets as postFix
     *
     * Ссылка на скачивание файла со списком отозв. сертификатов
     *
     * @return string
     */
    public function getPostFix()
    {
        return $this->postFix;
    }

    /**
     * Sets a new postFix
     *
     * Ссылка на скачивание файла со списком отозв. сертификатов
     *
     * @param string $postFix
     * @return static
     */
    public function setPostFix($postFix)
    {
        $this->postFix = $postFix;
        return $this;
    }

    /**
     * Gets as errorCode
     *
     * Код ошибки, при выполнении обновления списка отозванных сертификатов.
     *  Возможные значения: DICT_IS_ACTUAL, FAIL
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Sets a new errorCode
     *
     * Код ошибки, при выполнении обновления списка отозванных сертификатов.
     *  Возможные значения: DICT_IS_ACTUAL, FAIL
     *
     * @param string $errorCode
     * @return static
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * Gets as errorMsg
     *
     * Сообщение ошибки, при выполнении обновления списка отозванных сертификатов
     *
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * Sets a new errorMsg
     *
     * Сообщение ошибки, при выполнении обновления списка отозванных сертификатов
     *
     * @param string $errorMsg
     * @return static
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
        return $this;
    }


}

