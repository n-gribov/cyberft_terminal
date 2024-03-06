<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CashOrderType
 *
 * Заявка на получение наличных средств
 * XSD Type: CashOrder
 */
class CashOrderType extends DocBaseType
{

    /**
     * Идентификатор изменяемой заявки
     *
     * @property string $changeDocRef
     */
    private $changeDocRef = null;

    /**
     * Реквизиты документа
     *
     * @property \common\models\sbbolxml\request\DocDataCashOrderType $docDataCashOrder
     */
    private $docDataCashOrder = null;

    /**
     * Р/с
     *
     * @property \common\models\sbbolxml\request\AccountRubType $docAccount
     */
    private $docAccount = null;

    /**
     * Данные получателя
     *
     * @property \common\models\sbbolxml\request\CashOrderRecipientInfoType $recipientInfo
     */
    private $recipientInfo = null;

    /**
     * Заказы купюрности
     *
     * @property \common\models\sbbolxml\request\CashTokenReqType[] $cashTokenReqs
     */
    private $cashTokenReqs = null;

    /**
     * Цели снятия
     *
     * @property \common\models\sbbolxml\request\CashOrderPurposeType[] $purposes
     */
    private $purposes = null;

    /**
     * Gets as changeDocRef
     *
     * Идентификатор изменяемой заявки
     *
     * @return string
     */
    public function getChangeDocRef()
    {
        return $this->changeDocRef;
    }

    /**
     * Sets a new changeDocRef
     *
     * Идентификатор изменяемой заявки
     *
     * @param string $changeDocRef
     * @return static
     */
    public function setChangeDocRef($changeDocRef)
    {
        $this->changeDocRef = $changeDocRef;
        return $this;
    }

    /**
     * Gets as docDataCashOrder
     *
     * Реквизиты документа
     *
     * @return \common\models\sbbolxml\request\DocDataCashOrderType
     */
    public function getDocDataCashOrder()
    {
        return $this->docDataCashOrder;
    }

    /**
     * Sets a new docDataCashOrder
     *
     * Реквизиты документа
     *
     * @param \common\models\sbbolxml\request\DocDataCashOrderType $docDataCashOrder
     * @return static
     */
    public function setDocDataCashOrder(\common\models\sbbolxml\request\DocDataCashOrderType $docDataCashOrder)
    {
        $this->docDataCashOrder = $docDataCashOrder;
        return $this;
    }

    /**
     * Gets as docAccount
     *
     * Р/с
     *
     * @return \common\models\sbbolxml\request\AccountRubType
     */
    public function getDocAccount()
    {
        return $this->docAccount;
    }

    /**
     * Sets a new docAccount
     *
     * Р/с
     *
     * @param \common\models\sbbolxml\request\AccountRubType $docAccount
     * @return static
     */
    public function setDocAccount(\common\models\sbbolxml\request\AccountRubType $docAccount)
    {
        $this->docAccount = $docAccount;
        return $this;
    }

    /**
     * Gets as recipientInfo
     *
     * Данные получателя
     *
     * @return \common\models\sbbolxml\request\CashOrderRecipientInfoType
     */
    public function getRecipientInfo()
    {
        return $this->recipientInfo;
    }

    /**
     * Sets a new recipientInfo
     *
     * Данные получателя
     *
     * @param \common\models\sbbolxml\request\CashOrderRecipientInfoType $recipientInfo
     * @return static
     */
    public function setRecipientInfo(\common\models\sbbolxml\request\CashOrderRecipientInfoType $recipientInfo)
    {
        $this->recipientInfo = $recipientInfo;
        return $this;
    }

    /**
     * Adds as cashTokenReq
     *
     * Заказы купюрности
     *
     * @return static
     * @param \common\models\sbbolxml\request\CashTokenReqType $cashTokenReq
     */
    public function addToCashTokenReqs(\common\models\sbbolxml\request\CashTokenReqType $cashTokenReq)
    {
        $this->cashTokenReqs[] = $cashTokenReq;
        return $this;
    }

    /**
     * isset cashTokenReqs
     *
     * Заказы купюрности
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCashTokenReqs($index)
    {
        return isset($this->cashTokenReqs[$index]);
    }

    /**
     * unset cashTokenReqs
     *
     * Заказы купюрности
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCashTokenReqs($index)
    {
        unset($this->cashTokenReqs[$index]);
    }

    /**
     * Gets as cashTokenReqs
     *
     * Заказы купюрности
     *
     * @return \common\models\sbbolxml\request\CashTokenReqType[]
     */
    public function getCashTokenReqs()
    {
        return $this->cashTokenReqs;
    }

    /**
     * Sets a new cashTokenReqs
     *
     * Заказы купюрности
     *
     * @param \common\models\sbbolxml\request\CashTokenReqType[] $cashTokenReqs
     * @return static
     */
    public function setCashTokenReqs(array $cashTokenReqs)
    {
        $this->cashTokenReqs = $cashTokenReqs;
        return $this;
    }

    /**
     * Adds as cashOrderPurpose
     *
     * Цели снятия
     *
     * @return static
     * @param \common\models\sbbolxml\request\CashOrderPurposeType $cashOrderPurpose
     */
    public function addToPurposes(\common\models\sbbolxml\request\CashOrderPurposeType $cashOrderPurpose)
    {
        $this->purposes[] = $cashOrderPurpose;
        return $this;
    }

    /**
     * isset purposes
     *
     * Цели снятия
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPurposes($index)
    {
        return isset($this->purposes[$index]);
    }

    /**
     * unset purposes
     *
     * Цели снятия
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPurposes($index)
    {
        unset($this->purposes[$index]);
    }

    /**
     * Gets as purposes
     *
     * Цели снятия
     *
     * @return \common\models\sbbolxml\request\CashOrderPurposeType[]
     */
    public function getPurposes()
    {
        return $this->purposes;
    }

    /**
     * Sets a new purposes
     *
     * Цели снятия
     *
     * @param \common\models\sbbolxml\request\CashOrderPurposeType[] $purposes
     * @return static
     */
    public function setPurposes(array $purposes)
    {
        $this->purposes = $purposes;
        return $this;
    }


}

