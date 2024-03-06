<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing LetterOfDepositRaifType
 *
 *
 * XSD Type: LetterOfDepositRaif
 */
class LetterOfDepositRaifType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты платежного документа ДБО
     *
     * @property \common\models\raiffeisenxml\request\LetterOfDepositRaifType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Основные поля
     *
     * @property \common\models\raiffeisenxml\request\LetterOfDepositRaifType\MainAType $main
     */
    private $main = null;

    /**
     * В дату возврата Депозита просим вернуть
     *
     * @property \common\models\raiffeisenxml\request\LetterOfDepositRaifType\RefundAType $refund
     */
    private $refund = null;

    /**
     * Сумму начисленных процентов вернуть
     *
     * @property \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType $percentsRefund
     */
    private $percentsRefund = null;

    /**
     * Комментарии
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в УС
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в УС
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as docData
     *
     * Общие реквизиты платежного документа ДБО
     *
     * @return \common\models\raiffeisenxml\request\LetterOfDepositRaifType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты платежного документа ДБО
     *
     * @param \common\models\raiffeisenxml\request\LetterOfDepositRaifType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\LetterOfDepositRaifType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as main
     *
     * Основные поля
     *
     * @return \common\models\raiffeisenxml\request\LetterOfDepositRaifType\MainAType
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Sets a new main
     *
     * Основные поля
     *
     * @param \common\models\raiffeisenxml\request\LetterOfDepositRaifType\MainAType $main
     * @return static
     */
    public function setMain(\common\models\raiffeisenxml\request\LetterOfDepositRaifType\MainAType $main)
    {
        $this->main = $main;
        return $this;
    }

    /**
     * Gets as refund
     *
     * В дату возврата Депозита просим вернуть
     *
     * @return \common\models\raiffeisenxml\request\LetterOfDepositRaifType\RefundAType
     */
    public function getRefund()
    {
        return $this->refund;
    }

    /**
     * Sets a new refund
     *
     * В дату возврата Депозита просим вернуть
     *
     * @param \common\models\raiffeisenxml\request\LetterOfDepositRaifType\RefundAType $refund
     * @return static
     */
    public function setRefund(\common\models\raiffeisenxml\request\LetterOfDepositRaifType\RefundAType $refund)
    {
        $this->refund = $refund;
        return $this;
    }

    /**
     * Gets as percentsRefund
     *
     * Сумму начисленных процентов вернуть
     *
     * @return \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType
     */
    public function getPercentsRefund()
    {
        return $this->percentsRefund;
    }

    /**
     * Sets a new percentsRefund
     *
     * Сумму начисленных процентов вернуть
     *
     * @param \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType $percentsRefund
     * @return static
     */
    public function setPercentsRefund(\common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType $percentsRefund)
    {
        $this->percentsRefund = $percentsRefund;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Комментарии
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Комментарии
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}

