<?php

namespace common\models\sbbolxml\response\AdmOperationType;

/**
 * Class representing OperationInfoAType
 */
class OperationInfoAType
{

    /**
     * Идентификатор выписки в СББОЛ (UUID документа)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Идентификатор операции в СББОЛ
     *
     * @property string $operationID
     */
    private $operationID = null;

    /**
     * Дата и время операции
     *
     * @property \DateTime $operationTime
     */
    private $operationTime = null;

    /**
     * Сумма внесения средств
     *
     * @property float $operationSumm
     */
    private $operationSumm = null;

    /**
     * Комментарий к платежному документу
     *
     * @property string $payDocComment
     */
    private $payDocComment = null;

    /**
     * Идентификатор платежного документа
     *
     * @property string $payDocId
     */
    private $payDocId = null;

    /**
     * Номер платежного документа
     *
     * @property string $payDocNumber
     */
    private $payDocNumber = null;

    /**
     * Статус платежного документа
     *
     * @property string $payDocStateSystemName
     */
    private $payDocStateSystemName = null;

    /**
     * Причина отклонения
     *
     * @property string $rejectReason
     */
    private $rejectReason = null;

    /**
     * Gets as docId
     *
     * Идентификатор выписки в СББОЛ (UUID документа)
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор выписки в СББОЛ (UUID документа)
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as operationID
     *
     * Идентификатор операции в СББОЛ
     *
     * @return string
     */
    public function getOperationID()
    {
        return $this->operationID;
    }

    /**
     * Sets a new operationID
     *
     * Идентификатор операции в СББОЛ
     *
     * @param string $operationID
     * @return static
     */
    public function setOperationID($operationID)
    {
        $this->operationID = $operationID;
        return $this;
    }

    /**
     * Gets as operationTime
     *
     * Дата и время операции
     *
     * @return \DateTime
     */
    public function getOperationTime()
    {
        return $this->operationTime;
    }

    /**
     * Sets a new operationTime
     *
     * Дата и время операции
     *
     * @param \DateTime $operationTime
     * @return static
     */
    public function setOperationTime(\DateTime $operationTime)
    {
        $this->operationTime = $operationTime;
        return $this;
    }

    /**
     * Gets as operationSumm
     *
     * Сумма внесения средств
     *
     * @return float
     */
    public function getOperationSumm()
    {
        return $this->operationSumm;
    }

    /**
     * Sets a new operationSumm
     *
     * Сумма внесения средств
     *
     * @param float $operationSumm
     * @return static
     */
    public function setOperationSumm($operationSumm)
    {
        $this->operationSumm = $operationSumm;
        return $this;
    }

    /**
     * Gets as payDocComment
     *
     * Комментарий к платежному документу
     *
     * @return string
     */
    public function getPayDocComment()
    {
        return $this->payDocComment;
    }

    /**
     * Sets a new payDocComment
     *
     * Комментарий к платежному документу
     *
     * @param string $payDocComment
     * @return static
     */
    public function setPayDocComment($payDocComment)
    {
        $this->payDocComment = $payDocComment;
        return $this;
    }

    /**
     * Gets as payDocId
     *
     * Идентификатор платежного документа
     *
     * @return string
     */
    public function getPayDocId()
    {
        return $this->payDocId;
    }

    /**
     * Sets a new payDocId
     *
     * Идентификатор платежного документа
     *
     * @param string $payDocId
     * @return static
     */
    public function setPayDocId($payDocId)
    {
        $this->payDocId = $payDocId;
        return $this;
    }

    /**
     * Gets as payDocNumber
     *
     * Номер платежного документа
     *
     * @return string
     */
    public function getPayDocNumber()
    {
        return $this->payDocNumber;
    }

    /**
     * Sets a new payDocNumber
     *
     * Номер платежного документа
     *
     * @param string $payDocNumber
     * @return static
     */
    public function setPayDocNumber($payDocNumber)
    {
        $this->payDocNumber = $payDocNumber;
        return $this;
    }

    /**
     * Gets as payDocStateSystemName
     *
     * Статус платежного документа
     *
     * @return string
     */
    public function getPayDocStateSystemName()
    {
        return $this->payDocStateSystemName;
    }

    /**
     * Sets a new payDocStateSystemName
     *
     * Статус платежного документа
     *
     * @param string $payDocStateSystemName
     * @return static
     */
    public function setPayDocStateSystemName($payDocStateSystemName)
    {
        $this->payDocStateSystemName = $payDocStateSystemName;
        return $this;
    }

    /**
     * Gets as rejectReason
     *
     * Причина отклонения
     *
     * @return string
     */
    public function getRejectReason()
    {
        return $this->rejectReason;
    }

    /**
     * Sets a new rejectReason
     *
     * Причина отклонения
     *
     * @param string $rejectReason
     * @return static
     */
    public function setRejectReason($rejectReason)
    {
        $this->rejectReason = $rejectReason;
        return $this;
    }


}

