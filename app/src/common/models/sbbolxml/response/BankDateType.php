<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing BankDateType
 *
 *
 * XSD Type: BankDateType
 */
class BankDateType
{

    /**
     * Списания со счета Плательщика
     *
     * @property \DateTime $chargeOffDate
     */
    private $chargeOffDate = null;

    /**
     * Постановки в картотеку
     *
     * @property \DateTime $fileDate
     */
    private $fileDate = null;

    /**
     * Отметки банком Плательщика
     *
     * @property \DateTime $signDate
     */
    private $signDate = null;

    /**
     * Поступило в банк Плательщика
     *
     * @property \DateTime $receiptDate
     */
    private $receiptDate = null;

    /**
     * Дата перечисления платежа
     *
     * @property \DateTime $dpp
     */
    private $dpp = null;

    /**
     * Дата отметки банка Получателя
     *
     * @property \DateTime $recDate
     */
    private $recDate = null;

    /**
     * Дата отправки документа в Банк
     *
     * @property \DateTime $sendDate
     */
    private $sendDate = null;

    /**
     * Дата обработки документа
     *
     * @property \DateTime $processDate
     */
    private $processDate = null;

    /**
     * Дата/время исполнения Банком
     *
     * @property \DateTime $processDateTime
     */
    private $processDateTime = null;

    /**
     * Дата/время исполнения Банком
     *
     * @property \DateTime $statusDateTime
     */
    private $statusDateTime = null;

    /**
     * Gets as chargeOffDate
     *
     * Списания со счета Плательщика
     *
     * @return \DateTime
     */
    public function getChargeOffDate()
    {
        return $this->chargeOffDate;
    }

    /**
     * Sets a new chargeOffDate
     *
     * Списания со счета Плательщика
     *
     * @param \DateTime $chargeOffDate
     * @return static
     */
    public function setChargeOffDate(\DateTime $chargeOffDate)
    {
        $this->chargeOffDate = $chargeOffDate;
        return $this;
    }

    /**
     * Gets as fileDate
     *
     * Постановки в картотеку
     *
     * @return \DateTime
     */
    public function getFileDate()
    {
        return $this->fileDate;
    }

    /**
     * Sets a new fileDate
     *
     * Постановки в картотеку
     *
     * @param \DateTime $fileDate
     * @return static
     */
    public function setFileDate(\DateTime $fileDate)
    {
        $this->fileDate = $fileDate;
        return $this;
    }

    /**
     * Gets as signDate
     *
     * Отметки банком Плательщика
     *
     * @return \DateTime
     */
    public function getSignDate()
    {
        return $this->signDate;
    }

    /**
     * Sets a new signDate
     *
     * Отметки банком Плательщика
     *
     * @param \DateTime $signDate
     * @return static
     */
    public function setSignDate(\DateTime $signDate)
    {
        $this->signDate = $signDate;
        return $this;
    }

    /**
     * Gets as receiptDate
     *
     * Поступило в банк Плательщика
     *
     * @return \DateTime
     */
    public function getReceiptDate()
    {
        return $this->receiptDate;
    }

    /**
     * Sets a new receiptDate
     *
     * Поступило в банк Плательщика
     *
     * @param \DateTime $receiptDate
     * @return static
     */
    public function setReceiptDate(\DateTime $receiptDate)
    {
        $this->receiptDate = $receiptDate;
        return $this;
    }

    /**
     * Gets as dpp
     *
     * Дата перечисления платежа
     *
     * @return \DateTime
     */
    public function getDpp()
    {
        return $this->dpp;
    }

    /**
     * Sets a new dpp
     *
     * Дата перечисления платежа
     *
     * @param \DateTime $dpp
     * @return static
     */
    public function setDpp(\DateTime $dpp)
    {
        $this->dpp = $dpp;
        return $this;
    }

    /**
     * Gets as recDate
     *
     * Дата отметки банка Получателя
     *
     * @return \DateTime
     */
    public function getRecDate()
    {
        return $this->recDate;
    }

    /**
     * Sets a new recDate
     *
     * Дата отметки банка Получателя
     *
     * @param \DateTime $recDate
     * @return static
     */
    public function setRecDate(\DateTime $recDate)
    {
        $this->recDate = $recDate;
        return $this;
    }

    /**
     * Gets as sendDate
     *
     * Дата отправки документа в Банк
     *
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * Sets a new sendDate
     *
     * Дата отправки документа в Банк
     *
     * @param \DateTime $sendDate
     * @return static
     */
    public function setSendDate(\DateTime $sendDate)
    {
        $this->sendDate = $sendDate;
        return $this;
    }

    /**
     * Gets as processDate
     *
     * Дата обработки документа
     *
     * @return \DateTime
     */
    public function getProcessDate()
    {
        return $this->processDate;
    }

    /**
     * Sets a new processDate
     *
     * Дата обработки документа
     *
     * @param \DateTime $processDate
     * @return static
     */
    public function setProcessDate(\DateTime $processDate)
    {
        $this->processDate = $processDate;
        return $this;
    }

    /**
     * Gets as processDateTime
     *
     * Дата/время исполнения Банком
     *
     * @return \DateTime
     */
    public function getProcessDateTime()
    {
        return $this->processDateTime;
    }

    /**
     * Sets a new processDateTime
     *
     * Дата/время исполнения Банком
     *
     * @param \DateTime $processDateTime
     * @return static
     */
    public function setProcessDateTime(\DateTime $processDateTime)
    {
        $this->processDateTime = $processDateTime;
        return $this;
    }

    /**
     * Gets as statusDateTime
     *
     * Дата/время исполнения Банком
     *
     * @return \DateTime
     */
    public function getStatusDateTime()
    {
        return $this->statusDateTime;
    }

    /**
     * Sets a new statusDateTime
     *
     * Дата/время исполнения Банком
     *
     * @param \DateTime $statusDateTime
     * @return static
     */
    public function setStatusDateTime(\DateTime $statusDateTime)
    {
        $this->statusDateTime = $statusDateTime;
        return $this;
    }


}

