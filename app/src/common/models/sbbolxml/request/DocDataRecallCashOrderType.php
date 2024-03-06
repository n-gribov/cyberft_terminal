<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataRecallCashOrderType
 *
 * Реквизиты отмены заявки на получение наличных средств
 * XSD Type: DocDataRecallCashOrder
 */
class DocDataRecallCashOrderType
{

    /**
     * Номер документа
     *
     * @property string $docNumber
     */
    private $docNumber = null;

    /**
     * Дата создания документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Дата отменяемого документа
     *
     * @property \DateTime $recallDocDate
     */
    private $recallDocDate = null;

    /**
     * Номер отменяемого документа
     *
     * @property string $recallDocNum
     */
    private $recallDocNum = null;

    /**
     * Причина отмены
     *
     * @property string $reason
     */
    private $reason = null;

    /**
     * Gets as docNumber
     *
     * Номер документа
     *
     * @return string
     */
    public function getDocNumber()
    {
        return $this->docNumber;
    }

    /**
     * Sets a new docNumber
     *
     * Номер документа
     *
     * @param string $docNumber
     * @return static
     */
    public function setDocNumber($docNumber)
    {
        $this->docNumber = $docNumber;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата создания документа
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата создания документа
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as recallDocDate
     *
     * Дата отменяемого документа
     *
     * @return \DateTime
     */
    public function getRecallDocDate()
    {
        return $this->recallDocDate;
    }

    /**
     * Sets a new recallDocDate
     *
     * Дата отменяемого документа
     *
     * @param \DateTime $recallDocDate
     * @return static
     */
    public function setRecallDocDate(\DateTime $recallDocDate)
    {
        $this->recallDocDate = $recallDocDate;
        return $this;
    }

    /**
     * Gets as recallDocNum
     *
     * Номер отменяемого документа
     *
     * @return string
     */
    public function getRecallDocNum()
    {
        return $this->recallDocNum;
    }

    /**
     * Sets a new recallDocNum
     *
     * Номер отменяемого документа
     *
     * @param string $recallDocNum
     * @return static
     */
    public function setRecallDocNum($recallDocNum)
    {
        $this->recallDocNum = $recallDocNum;
        return $this;
    }

    /**
     * Gets as reason
     *
     * Причина отмены
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets a new reason
     *
     * Причина отмены
     *
     * @param string $reason
     * @return static
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }


}

