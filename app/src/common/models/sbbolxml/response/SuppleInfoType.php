<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing SuppleInfoType
 *
 * Справочная информация из банка
 *  Неактуально при переключении на Инструкцию 181-И
 * XSD Type: SuppleInfo
 */
class SuppleInfoType
{

    /**
     * Способ направления резиденту оформленного (переоформленного, принятого на обслуживание, закрытого) ПС
     *  1 – на бумажном носителе
     *  2 – в электронном виде
     *
     * @property string $sendMethod
     */
    private $sendMethod = null;

    /**
     * Способ представления резидентом документов для оформления (переоформления, принятия на обслуживание, закрытия) ПС
     *  1 – на бумажном носителе
     *  2 – в электронном виде.
     *
     * @property string $deliveryMethod
     */
    private $deliveryMethod = null;

    /**
     * Дата направления резиденту оформленного (переоформленного, принятого на обслуживание, закрытого) ПС
     *
     * @property \DateTime $sendData
     */
    private $sendData = null;

    /**
     * Дата представления резидентом документов для оформления (переоформления, принятия на обслуживание, закрытия) ПС
     *
     * @property \DateTime $deliveryData
     */
    private $deliveryData = null;

    /**
     * Дата поступления в банк
     *
     * @property \DateTime $receiptDate
     */
    private $receiptDate = null;

    /**
     * Дата принятия/возврата валютным контролем
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Gets as sendMethod
     *
     * Способ направления резиденту оформленного (переоформленного, принятого на обслуживание, закрытого) ПС
     *  1 – на бумажном носителе
     *  2 – в электронном виде
     *
     * @return string
     */
    public function getSendMethod()
    {
        return $this->sendMethod;
    }

    /**
     * Sets a new sendMethod
     *
     * Способ направления резиденту оформленного (переоформленного, принятого на обслуживание, закрытого) ПС
     *  1 – на бумажном носителе
     *  2 – в электронном виде
     *
     * @param string $sendMethod
     * @return static
     */
    public function setSendMethod($sendMethod)
    {
        $this->sendMethod = $sendMethod;
        return $this;
    }

    /**
     * Gets as deliveryMethod
     *
     * Способ представления резидентом документов для оформления (переоформления, принятия на обслуживание, закрытия) ПС
     *  1 – на бумажном носителе
     *  2 – в электронном виде.
     *
     * @return string
     */
    public function getDeliveryMethod()
    {
        return $this->deliveryMethod;
    }

    /**
     * Sets a new deliveryMethod
     *
     * Способ представления резидентом документов для оформления (переоформления, принятия на обслуживание, закрытия) ПС
     *  1 – на бумажном носителе
     *  2 – в электронном виде.
     *
     * @param string $deliveryMethod
     * @return static
     */
    public function setDeliveryMethod($deliveryMethod)
    {
        $this->deliveryMethod = $deliveryMethod;
        return $this;
    }

    /**
     * Gets as sendData
     *
     * Дата направления резиденту оформленного (переоформленного, принятого на обслуживание, закрытого) ПС
     *
     * @return \DateTime
     */
    public function getSendData()
    {
        return $this->sendData;
    }

    /**
     * Sets a new sendData
     *
     * Дата направления резиденту оформленного (переоформленного, принятого на обслуживание, закрытого) ПС
     *
     * @param \DateTime $sendData
     * @return static
     */
    public function setSendData(\DateTime $sendData)
    {
        $this->sendData = $sendData;
        return $this;
    }

    /**
     * Gets as deliveryData
     *
     * Дата представления резидентом документов для оформления (переоформления, принятия на обслуживание, закрытия) ПС
     *
     * @return \DateTime
     */
    public function getDeliveryData()
    {
        return $this->deliveryData;
    }

    /**
     * Sets a new deliveryData
     *
     * Дата представления резидентом документов для оформления (переоформления, принятия на обслуживание, закрытия) ПС
     *
     * @param \DateTime $deliveryData
     * @return static
     */
    public function setDeliveryData(\DateTime $deliveryData)
    {
        $this->deliveryData = $deliveryData;
        return $this;
    }

    /**
     * Gets as receiptDate
     *
     * Дата поступления в банк
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
     * Дата поступления в банк
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
     * Gets as valueDate
     *
     * Дата принятия/возврата валютным контролем
     *
     * @return \DateTime
     */
    public function getValueDate()
    {
        return $this->valueDate;
    }

    /**
     * Sets a new valueDate
     *
     * Дата принятия/возврата валютным контролем
     *
     * @param \DateTime $valueDate
     * @return static
     */
    public function setValueDate(\DateTime $valueDate)
    {
        $this->valueDate = $valueDate;
        return $this;
    }


}

