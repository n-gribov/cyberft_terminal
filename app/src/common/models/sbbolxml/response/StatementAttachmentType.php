<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing StatementAttachmentType
 *
 *
 * XSD Type: StatementAttachmentType
 */
class StatementAttachmentType
{

    /**
     * Номер счёта
     *
     * @property string $account
     */
    private $account = null;

    /**
     * Дата начала периода
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата окончания периода
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Формат предоставления выписки
     *
     * @property string $statementFormat
     */
    private $statementFormat = null;

    /**
     * Данные выписки
     *
     * @property string $body
     */
    private $body = null;

    /**
     * Gets as account
     *
     * Номер счёта
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Номер счёта
     *
     * @param string $account
     * @return static
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Gets as beginDate
     *
     * Дата начала периода
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
     * Дата начала периода
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
     * Дата окончания периода
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
     * Дата окончания периода
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
     * Gets as statementFormat
     *
     * Формат предоставления выписки
     *
     * @return string
     */
    public function getStatementFormat()
    {
        return $this->statementFormat;
    }

    /**
     * Sets a new statementFormat
     *
     * Формат предоставления выписки
     *
     * @param string $statementFormat
     * @return static
     */
    public function setStatementFormat($statementFormat)
    {
        $this->statementFormat = $statementFormat;
        return $this;
    }

    /**
     * Gets as body
     *
     * Данные выписки
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets a new body
     *
     * Данные выписки
     *
     * @param string $body
     * @return static
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }


}

