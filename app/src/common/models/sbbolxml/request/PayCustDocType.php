<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PayCustDocType
 *
 * Заявка на оплату документа таможни
 * XSD Type: PayCustDoc
 */
class PayCustDocType extends DocBaseType
{

    /**
     * ID таможенной карты
     *
     * @property string $customCardId
     */
    private $customCardId = null;

    /**
     * ID ТО (идентификатор Таможенного Оператора) в СББОЛ
     *
     * @property string $customOperatorID
     */
    private $customOperatorID = null;

    /**
     * Время, в течение которого ИС Банка может отправить в ИС ТО Сообщение об акцепте по конкретной Заявке
     *
     * @property \DateTime $deadLine
     */
    private $deadLine = null;

    /**
     * Реквизиты платёжного документа
     *
     * @property \common\models\sbbolxml\request\PayCustDocAccDocType $accDoc
     */
    private $accDoc = null;

    /**
     * Параметры таможенного платежа
     *
     * @property \common\models\sbbolxml\request\PayCustDocParamsType $payCustDocParams
     */
    private $payCustDocParams = null;

    /**
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @property \common\models\sbbolxml\request\DepartmentalInfoType $departmentalInfo
     */
    private $departmentalInfo = null;

    /**
     * Gets as customCardId
     *
     * ID таможенной карты
     *
     * @return string
     */
    public function getCustomCardId()
    {
        return $this->customCardId;
    }

    /**
     * Sets a new customCardId
     *
     * ID таможенной карты
     *
     * @param string $customCardId
     * @return static
     */
    public function setCustomCardId($customCardId)
    {
        $this->customCardId = $customCardId;
        return $this;
    }

    /**
     * Gets as customOperatorID
     *
     * ID ТО (идентификатор Таможенного Оператора) в СББОЛ
     *
     * @return string
     */
    public function getCustomOperatorID()
    {
        return $this->customOperatorID;
    }

    /**
     * Sets a new customOperatorID
     *
     * ID ТО (идентификатор Таможенного Оператора) в СББОЛ
     *
     * @param string $customOperatorID
     * @return static
     */
    public function setCustomOperatorID($customOperatorID)
    {
        $this->customOperatorID = $customOperatorID;
        return $this;
    }

    /**
     * Gets as deadLine
     *
     * Время, в течение которого ИС Банка может отправить в ИС ТО Сообщение об акцепте по конкретной Заявке
     *
     * @return \DateTime
     */
    public function getDeadLine()
    {
        return $this->deadLine;
    }

    /**
     * Sets a new deadLine
     *
     * Время, в течение которого ИС Банка может отправить в ИС ТО Сообщение об акцепте по конкретной Заявке
     *
     * @param \DateTime $deadLine
     * @return static
     */
    public function setDeadLine(\DateTime $deadLine)
    {
        $this->deadLine = $deadLine;
        return $this;
    }

    /**
     * Gets as accDoc
     *
     * Реквизиты платёжного документа
     *
     * @return \common\models\sbbolxml\request\PayCustDocAccDocType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Реквизиты платёжного документа
     *
     * @param \common\models\sbbolxml\request\PayCustDocAccDocType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\sbbolxml\request\PayCustDocAccDocType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as payCustDocParams
     *
     * Параметры таможенного платежа
     *
     * @return \common\models\sbbolxml\request\PayCustDocParamsType
     */
    public function getPayCustDocParams()
    {
        return $this->payCustDocParams;
    }

    /**
     * Sets a new payCustDocParams
     *
     * Параметры таможенного платежа
     *
     * @param \common\models\sbbolxml\request\PayCustDocParamsType $payCustDocParams
     * @return static
     */
    public function setPayCustDocParams(\common\models\sbbolxml\request\PayCustDocParamsType $payCustDocParams)
    {
        $this->payCustDocParams = $payCustDocParams;
        return $this;
    }

    /**
     * Gets as departmentalInfo
     *
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @return \common\models\sbbolxml\request\DepartmentalInfoType
     */
    public function getDepartmentalInfo()
    {
        return $this->departmentalInfo;
    }

    /**
     * Sets a new departmentalInfo
     *
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @param \common\models\sbbolxml\request\DepartmentalInfoType $departmentalInfo
     * @return static
     */
    public function setDepartmentalInfo(\common\models\sbbolxml\request\DepartmentalInfoType $departmentalInfo)
    {
        $this->departmentalInfo = $departmentalInfo;
        return $this;
    }


}

