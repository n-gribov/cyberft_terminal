<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AcceptInfoType
 *
 *
 * XSD Type: AcceptInfo
 */
class AcceptInfoType
{

    /**
     * Номер платежного требования
     *
     * @property string $numDocPayReq
     */
    private $numDocPayReq = null;

    /**
     * Дата платежного требования
     *
     * @property \DateTime $dateDocPayReq
     */
    private $dateDocPayReq = null;

    /**
     * Реквизиты плательщика
     *
     * @property \common\models\sbbolxml\request\ClientType $payer
     */
    private $payer = null;

    /**
     * Реквизиты получателя
     *
     * @property \common\models\sbbolxml\request\ContragentType $payee
     */
    private $payee = null;

    /**
     * Сумма исходного платежного требования
     *
     * @property float $summaTreb
     */
    private $summaTreb = null;

    /**
     * Дата окончания акцепта по платежному требованию
     *
     * @property \DateTime $dateOD
     */
    private $dateOD = null;

    /**
     * Нарушен пункт договора
     *
     * @property string $punktDog
     */
    private $punktDog = null;

    /**
     * Номер договора
     *
     * @property string $numDog
     */
    private $numDog = null;

    /**
     * Дата договора
     *
     * @property \DateTime $dateDog
     */
    private $dateDog = null;

    /**
     * Идентификатор исходного платежного требования в АБС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Комментарии к заявлению на акцепт. Заполняется при отказе от акцепта, указывается
     *  причина отказа от акцепта.я
     *
     * @property string $comments
     */
    private $comments = null;

    /**
     * Дата поступления платежного требования в Банк
     *
     * @property \DateTime $dateOfReceipt
     */
    private $dateOfReceipt = null;

    /**
     * Gets as numDocPayReq
     *
     * Номер платежного требования
     *
     * @return string
     */
    public function getNumDocPayReq()
    {
        return $this->numDocPayReq;
    }

    /**
     * Sets a new numDocPayReq
     *
     * Номер платежного требования
     *
     * @param string $numDocPayReq
     * @return static
     */
    public function setNumDocPayReq($numDocPayReq)
    {
        $this->numDocPayReq = $numDocPayReq;
        return $this;
    }

    /**
     * Gets as dateDocPayReq
     *
     * Дата платежного требования
     *
     * @return \DateTime
     */
    public function getDateDocPayReq()
    {
        return $this->dateDocPayReq;
    }

    /**
     * Sets a new dateDocPayReq
     *
     * Дата платежного требования
     *
     * @param \DateTime $dateDocPayReq
     * @return static
     */
    public function setDateDocPayReq(\DateTime $dateDocPayReq)
    {
        $this->dateDocPayReq = $dateDocPayReq;
        return $this;
    }

    /**
     * Gets as payer
     *
     * Реквизиты плательщика
     *
     * @return \common\models\sbbolxml\request\ClientType
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Sets a new payer
     *
     * Реквизиты плательщика
     *
     * @param \common\models\sbbolxml\request\ClientType $payer
     * @return static
     */
    public function setPayer(\common\models\sbbolxml\request\ClientType $payer)
    {
        $this->payer = $payer;
        return $this;
    }

    /**
     * Gets as payee
     *
     * Реквизиты получателя
     *
     * @return \common\models\sbbolxml\request\ContragentType
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * Sets a new payee
     *
     * Реквизиты получателя
     *
     * @param \common\models\sbbolxml\request\ContragentType $payee
     * @return static
     */
    public function setPayee(\common\models\sbbolxml\request\ContragentType $payee)
    {
        $this->payee = $payee;
        return $this;
    }

    /**
     * Gets as summaTreb
     *
     * Сумма исходного платежного требования
     *
     * @return float
     */
    public function getSummaTreb()
    {
        return $this->summaTreb;
    }

    /**
     * Sets a new summaTreb
     *
     * Сумма исходного платежного требования
     *
     * @param float $summaTreb
     * @return static
     */
    public function setSummaTreb($summaTreb)
    {
        $this->summaTreb = $summaTreb;
        return $this;
    }

    /**
     * Gets as dateOD
     *
     * Дата окончания акцепта по платежному требованию
     *
     * @return \DateTime
     */
    public function getDateOD()
    {
        return $this->dateOD;
    }

    /**
     * Sets a new dateOD
     *
     * Дата окончания акцепта по платежному требованию
     *
     * @param \DateTime $dateOD
     * @return static
     */
    public function setDateOD(\DateTime $dateOD)
    {
        $this->dateOD = $dateOD;
        return $this;
    }

    /**
     * Gets as punktDog
     *
     * Нарушен пункт договора
     *
     * @return string
     */
    public function getPunktDog()
    {
        return $this->punktDog;
    }

    /**
     * Sets a new punktDog
     *
     * Нарушен пункт договора
     *
     * @param string $punktDog
     * @return static
     */
    public function setPunktDog($punktDog)
    {
        $this->punktDog = $punktDog;
        return $this;
    }

    /**
     * Gets as numDog
     *
     * Номер договора
     *
     * @return string
     */
    public function getNumDog()
    {
        return $this->numDog;
    }

    /**
     * Sets a new numDog
     *
     * Номер договора
     *
     * @param string $numDog
     * @return static
     */
    public function setNumDog($numDog)
    {
        $this->numDog = $numDog;
        return $this;
    }

    /**
     * Gets as dateDog
     *
     * Дата договора
     *
     * @return \DateTime
     */
    public function getDateDog()
    {
        return $this->dateDog;
    }

    /**
     * Sets a new dateDog
     *
     * Дата договора
     *
     * @param \DateTime $dateDog
     * @return static
     */
    public function setDateDog(\DateTime $dateDog)
    {
        $this->dateDog = $dateDog;
        return $this;
    }

    /**
     * Gets as docExtId
     *
     * Идентификатор исходного платежного требования в АБС
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
     * Идентификатор исходного платежного требования в АБС
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
     * Gets as comments
     *
     * Комментарии к заявлению на акцепт. Заполняется при отказе от акцепта, указывается
     *  причина отказа от акцепта.я
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Sets a new comments
     *
     * Комментарии к заявлению на акцепт. Заполняется при отказе от акцепта, указывается
     *  причина отказа от акцепта.я
     *
     * @param string $comments
     * @return static
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Gets as dateOfReceipt
     *
     * Дата поступления платежного требования в Банк
     *
     * @return \DateTime
     */
    public function getDateOfReceipt()
    {
        return $this->dateOfReceipt;
    }

    /**
     * Sets a new dateOfReceipt
     *
     * Дата поступления платежного требования в Банк
     *
     * @param \DateTime $dateOfReceipt
     * @return static
     */
    public function setDateOfReceipt(\DateTime $dateOfReceipt)
    {
        $this->dateOfReceipt = $dateOfReceipt;
        return $this;
    }


}

