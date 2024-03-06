<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DigitalSignType
 *
 *
 * XSD Type: DigitalSign
 */
class DigitalSignType
{

    /**
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
     *
     * @property string $issuer
     */
    private $issuer = null;

    /**
     * Серийный номер сертификата
     *
     * @property string $sN
     */
    private $sN = null;

    /**
     * Значение ЭП
     *
     * @property string $value
     */
    private $value = null;

    /**
     * Имя схемы подписи
     *
     * @property string $digestName
     */
    private $digestName = null;

    /**
     * Версия схемы подписи
     *
     * @property string $digestVersion
     */
    private $digestVersion = null;

    /**
     * Уникальные свойтсва компьютера отправителя (ХЭШ)
     *
     * @property string $pcPropHash
     */
    private $pcPropHash = null;

    /**
     * Данные для fraud-мониторинга. Информация о подписи
     *
     * @property \common\models\sbbolxml\request\FraudType $fraud
     */
    private $fraud = null;

    /**
     * Очередность наложения подписи (какая подпись когда была наложена) в случае
     *  наложения подписи и удаления ее, порядок должен сдвигаться соответственно влево
     *
     * @property integer $order
     */
    private $order = null;

    /**
     * Дата/Время подписи
     *  Пример: 2012-04-13 09:44:59.003
     *
     * @property \DateTime $signDate
     */
    private $signDate = null;

    /**
     * Gets as issuer
     *
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
     *
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * Sets a new issuer
     *
     * Идентификатор поставщика сертификата
     *  Например, " issuer="E=IdleCA, C=RU, S=Idle CA, L=Idle CA, O=Idle CA, OU=Idle CA, CN=Idle CA" "
     *
     * @param string $issuer
     * @return static
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * Gets as sN
     *
     * Серийный номер сертификата
     *
     * @return string
     */
    public function getSN()
    {
        return $this->sN;
    }

    /**
     * Sets a new sN
     *
     * Серийный номер сертификата
     *
     * @param string $sN
     * @return static
     */
    public function setSN($sN)
    {
        $this->sN = $sN;
        return $this;
    }

    /**
     * Gets as value
     *
     * Значение ЭП
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets a new value
     *
     * Значение ЭП
     *
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Gets as digestName
     *
     * Имя схемы подписи
     *
     * @return string
     */
    public function getDigestName()
    {
        return $this->digestName;
    }

    /**
     * Sets a new digestName
     *
     * Имя схемы подписи
     *
     * @param string $digestName
     * @return static
     */
    public function setDigestName($digestName)
    {
        $this->digestName = $digestName;
        return $this;
    }

    /**
     * Gets as digestVersion
     *
     * Версия схемы подписи
     *
     * @return string
     */
    public function getDigestVersion()
    {
        return $this->digestVersion;
    }

    /**
     * Sets a new digestVersion
     *
     * Версия схемы подписи
     *
     * @param string $digestVersion
     * @return static
     */
    public function setDigestVersion($digestVersion)
    {
        $this->digestVersion = $digestVersion;
        return $this;
    }

    /**
     * Gets as pcPropHash
     *
     * Уникальные свойтсва компьютера отправителя (ХЭШ)
     *
     * @return string
     */
    public function getPcPropHash()
    {
        return $this->pcPropHash;
    }

    /**
     * Sets a new pcPropHash
     *
     * Уникальные свойтсва компьютера отправителя (ХЭШ)
     *
     * @param string $pcPropHash
     * @return static
     */
    public function setPcPropHash($pcPropHash)
    {
        $this->pcPropHash = $pcPropHash;
        return $this;
    }

    /**
     * Gets as fraud
     *
     * Данные для fraud-мониторинга. Информация о подписи
     *
     * @return \common\models\sbbolxml\request\FraudType
     */
    public function getFraud()
    {
        return $this->fraud;
    }

    /**
     * Sets a new fraud
     *
     * Данные для fraud-мониторинга. Информация о подписи
     *
     * @param \common\models\sbbolxml\request\FraudType $fraud
     * @return static
     */
    public function setFraud(\common\models\sbbolxml\request\FraudType $fraud)
    {
        $this->fraud = $fraud;
        return $this;
    }

    /**
     * Gets as order
     *
     * Очередность наложения подписи (какая подпись когда была наложена) в случае
     *  наложения подписи и удаления ее, порядок должен сдвигаться соответственно влево
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets a new order
     *
     * Очередность наложения подписи (какая подпись когда была наложена) в случае
     *  наложения подписи и удаления ее, порядок должен сдвигаться соответственно влево
     *
     * @param integer $order
     * @return static
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Gets as signDate
     *
     * Дата/Время подписи
     *  Пример: 2012-04-13 09:44:59.003
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
     * Дата/Время подписи
     *  Пример: 2012-04-13 09:44:59.003
     *
     * @param \DateTime $signDate
     * @return static
     */
    public function setSignDate(\DateTime $signDate)
    {
        $this->signDate = $signDate;
        return $this;
    }


}

