<?php

namespace common\models\sbbolxml\response\TicketType\InfoAType;

/**
 * Class representing AddInfoAType
 */
class AddInfoAType
{

    /**
     * Доп. информация по прикреплению счетов дочерней
     *  организации к головной компании холдинга
     *
     * @property \common\models\sbbolxml\response\HoldingInfoTicketType $holdingInfo
     */
    private $holdingInfo = null;

    /**
     * Доп. информация для квитанции по платежному поручению
     *
     * @property \common\models\sbbolxml\response\PayDocRuTicketType $payDocRu
     */
    private $payDocRu = null;

    /**
     * Доп. информация для квитанции по поручению на перевод валюты
     *
     * @property \common\models\sbbolxml\response\PayDocCurTicketType $payDocCur
     */
    private $payDocCur = null;

    /**
     * Доп. информация для квитанции по поручению на покупку, продажу, конверсию
     *
     * @property \common\models\sbbolxml\response\CurrConvTicketType $currConv
     */
    private $currConv = null;

    /**
     * @property \common\models\sbbolxml\response\MandatorySaleTicketType $mandatorySale
     */
    private $mandatorySale = null;

    /**
     * Остатки по счетам
     *
     * @property \common\models\sbbolxml\response\RemainResponseType $remainResponse
     */
    private $remainResponse = null;

    /**
     * Электронный реестр (Зарплатная ведомость)
     *
     * @property \common\models\sbbolxml\response\ImplSalaryDocType $implSalaryDoc
     */
    private $implSalaryDoc = null;

    /**
     * Электронный реестр на открытие счетов и выпуск карт
     *
     * @property \common\models\sbbolxml\response\ImplRegOfIssCardsType $regOfIssCards
     */
    private $regOfIssCards = null;

    /**
     * Бумажные копии запроса на КСКП ЭП
     *
     * @property \common\models\sbbolxml\response\CertiRequestTicketType $certifRequestQualified
     */
    private $certifRequestQualified = null;

    /**
     * Информация по валютному контролю
     *
     * @property \common\models\sbbolxml\response\CurrControlInfoType $currControlInfo
     */
    private $currControlInfo = null;

    /**
     * Дополнительная информация по контрагенту
     *
     * @property \common\models\sbbolxml\response\ContragentAddTicketType $contragentAddTicket
     */
    private $contragentAddTicket = null;

    /**
     * Дополнительная информация по заявке на получение наличных средств
     *
     * @property \common\models\sbbolxml\response\CashOrderTicketType $cashOrderTicket
     */
    private $cashOrderTicket = null;

    /**
     * @property \common\models\sbbolxml\response\BigFilesStatusType[] $bigFilesStatuses
     */
    private $bigFilesStatuses = null;

    /**
     * Дополнительная информация по ПСФ
     *
     * @property \common\models\sbbolxml\response\GenericLetterToBankTicketType $genericLetterToBankTicket
     */
    private $genericLetterToBankTicket = null;

    /**
     * Gets as holdingInfo
     *
     * Доп. информация по прикреплению счетов дочерней
     *  организации к головной компании холдинга
     *
     * @return \common\models\sbbolxml\response\HoldingInfoTicketType
     */
    public function getHoldingInfo()
    {
        return $this->holdingInfo;
    }

    /**
     * Sets a new holdingInfo
     *
     * Доп. информация по прикреплению счетов дочерней
     *  организации к головной компании холдинга
     *
     * @param \common\models\sbbolxml\response\HoldingInfoTicketType $holdingInfo
     * @return static
     */
    public function setHoldingInfo(\common\models\sbbolxml\response\HoldingInfoTicketType $holdingInfo)
    {
        $this->holdingInfo = $holdingInfo;
        return $this;
    }

    /**
     * Gets as payDocRu
     *
     * Доп. информация для квитанции по платежному поручению
     *
     * @return \common\models\sbbolxml\response\PayDocRuTicketType
     */
    public function getPayDocRu()
    {
        return $this->payDocRu;
    }

    /**
     * Sets a new payDocRu
     *
     * Доп. информация для квитанции по платежному поручению
     *
     * @param \common\models\sbbolxml\response\PayDocRuTicketType $payDocRu
     * @return static
     */
    public function setPayDocRu(\common\models\sbbolxml\response\PayDocRuTicketType $payDocRu)
    {
        $this->payDocRu = $payDocRu;
        return $this;
    }

    /**
     * Gets as payDocCur
     *
     * Доп. информация для квитанции по поручению на перевод валюты
     *
     * @return \common\models\sbbolxml\response\PayDocCurTicketType
     */
    public function getPayDocCur()
    {
        return $this->payDocCur;
    }

    /**
     * Sets a new payDocCur
     *
     * Доп. информация для квитанции по поручению на перевод валюты
     *
     * @param \common\models\sbbolxml\response\PayDocCurTicketType $payDocCur
     * @return static
     */
    public function setPayDocCur(\common\models\sbbolxml\response\PayDocCurTicketType $payDocCur)
    {
        $this->payDocCur = $payDocCur;
        return $this;
    }

    /**
     * Gets as currConv
     *
     * Доп. информация для квитанции по поручению на покупку, продажу, конверсию
     *
     * @return \common\models\sbbolxml\response\CurrConvTicketType
     */
    public function getCurrConv()
    {
        return $this->currConv;
    }

    /**
     * Sets a new currConv
     *
     * Доп. информация для квитанции по поручению на покупку, продажу, конверсию
     *
     * @param \common\models\sbbolxml\response\CurrConvTicketType $currConv
     * @return static
     */
    public function setCurrConv(\common\models\sbbolxml\response\CurrConvTicketType $currConv)
    {
        $this->currConv = $currConv;
        return $this;
    }

    /**
     * Gets as mandatorySale
     *
     * @return \common\models\sbbolxml\response\MandatorySaleTicketType
     */
    public function getMandatorySale()
    {
        return $this->mandatorySale;
    }

    /**
     * Sets a new mandatorySale
     *
     * @param \common\models\sbbolxml\response\MandatorySaleTicketType $mandatorySale
     * @return static
     */
    public function setMandatorySale(\common\models\sbbolxml\response\MandatorySaleTicketType $mandatorySale)
    {
        $this->mandatorySale = $mandatorySale;
        return $this;
    }

    /**
     * Gets as remainResponse
     *
     * Остатки по счетам
     *
     * @return \common\models\sbbolxml\response\RemainResponseType
     */
    public function getRemainResponse()
    {
        return $this->remainResponse;
    }

    /**
     * Sets a new remainResponse
     *
     * Остатки по счетам
     *
     * @param \common\models\sbbolxml\response\RemainResponseType $remainResponse
     * @return static
     */
    public function setRemainResponse(\common\models\sbbolxml\response\RemainResponseType $remainResponse)
    {
        $this->remainResponse = $remainResponse;
        return $this;
    }

    /**
     * Gets as implSalaryDoc
     *
     * Электронный реестр (Зарплатная ведомость)
     *
     * @return \common\models\sbbolxml\response\ImplSalaryDocType
     */
    public function getImplSalaryDoc()
    {
        return $this->implSalaryDoc;
    }

    /**
     * Sets a new implSalaryDoc
     *
     * Электронный реестр (Зарплатная ведомость)
     *
     * @param \common\models\sbbolxml\response\ImplSalaryDocType $implSalaryDoc
     * @return static
     */
    public function setImplSalaryDoc(\common\models\sbbolxml\response\ImplSalaryDocType $implSalaryDoc)
    {
        $this->implSalaryDoc = $implSalaryDoc;
        return $this;
    }

    /**
     * Gets as regOfIssCards
     *
     * Электронный реестр на открытие счетов и выпуск карт
     *
     * @return \common\models\sbbolxml\response\ImplRegOfIssCardsType
     */
    public function getRegOfIssCards()
    {
        return $this->regOfIssCards;
    }

    /**
     * Sets a new regOfIssCards
     *
     * Электронный реестр на открытие счетов и выпуск карт
     *
     * @param \common\models\sbbolxml\response\ImplRegOfIssCardsType $regOfIssCards
     * @return static
     */
    public function setRegOfIssCards(\common\models\sbbolxml\response\ImplRegOfIssCardsType $regOfIssCards)
    {
        $this->regOfIssCards = $regOfIssCards;
        return $this;
    }

    /**
     * Gets as certifRequestQualified
     *
     * Бумажные копии запроса на КСКП ЭП
     *
     * @return \common\models\sbbolxml\response\CertiRequestTicketType
     */
    public function getCertifRequestQualified()
    {
        return $this->certifRequestQualified;
    }

    /**
     * Sets a new certifRequestQualified
     *
     * Бумажные копии запроса на КСКП ЭП
     *
     * @param \common\models\sbbolxml\response\CertiRequestTicketType $certifRequestQualified
     * @return static
     */
    public function setCertifRequestQualified(\common\models\sbbolxml\response\CertiRequestTicketType $certifRequestQualified)
    {
        $this->certifRequestQualified = $certifRequestQualified;
        return $this;
    }

    /**
     * Gets as currControlInfo
     *
     * Информация по валютному контролю
     *
     * @return \common\models\sbbolxml\response\CurrControlInfoType
     */
    public function getCurrControlInfo()
    {
        return $this->currControlInfo;
    }

    /**
     * Sets a new currControlInfo
     *
     * Информация по валютному контролю
     *
     * @param \common\models\sbbolxml\response\CurrControlInfoType $currControlInfo
     * @return static
     */
    public function setCurrControlInfo(\common\models\sbbolxml\response\CurrControlInfoType $currControlInfo)
    {
        $this->currControlInfo = $currControlInfo;
        return $this;
    }

    /**
     * Gets as contragentAddTicket
     *
     * Дополнительная информация по контрагенту
     *
     * @return \common\models\sbbolxml\response\ContragentAddTicketType
     */
    public function getContragentAddTicket()
    {
        return $this->contragentAddTicket;
    }

    /**
     * Sets a new contragentAddTicket
     *
     * Дополнительная информация по контрагенту
     *
     * @param \common\models\sbbolxml\response\ContragentAddTicketType $contragentAddTicket
     * @return static
     */
    public function setContragentAddTicket(\common\models\sbbolxml\response\ContragentAddTicketType $contragentAddTicket)
    {
        $this->contragentAddTicket = $contragentAddTicket;
        return $this;
    }

    /**
     * Gets as cashOrderTicket
     *
     * Дополнительная информация по заявке на получение наличных средств
     *
     * @return \common\models\sbbolxml\response\CashOrderTicketType
     */
    public function getCashOrderTicket()
    {
        return $this->cashOrderTicket;
    }

    /**
     * Sets a new cashOrderTicket
     *
     * Дополнительная информация по заявке на получение наличных средств
     *
     * @param \common\models\sbbolxml\response\CashOrderTicketType $cashOrderTicket
     * @return static
     */
    public function setCashOrderTicket(\common\models\sbbolxml\response\CashOrderTicketType $cashOrderTicket)
    {
        $this->cashOrderTicket = $cashOrderTicket;
        return $this;
    }

    /**
     * Adds as bigFilesStatus
     *
     * @return static
     * @param \common\models\sbbolxml\response\BigFilesStatusType $bigFilesStatus
     */
    public function addToBigFilesStatuses(\common\models\sbbolxml\response\BigFilesStatusType $bigFilesStatus)
    {
        $this->bigFilesStatuses[] = $bigFilesStatus;
        return $this;
    }

    /**
     * isset bigFilesStatuses
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBigFilesStatuses($index)
    {
        return isset($this->bigFilesStatuses[$index]);
    }

    /**
     * unset bigFilesStatuses
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBigFilesStatuses($index)
    {
        unset($this->bigFilesStatuses[$index]);
    }

    /**
     * Gets as bigFilesStatuses
     *
     * @return \common\models\sbbolxml\response\BigFilesStatusType[]
     */
    public function getBigFilesStatuses()
    {
        return $this->bigFilesStatuses;
    }

    /**
     * Sets a new bigFilesStatuses
     *
     * @param \common\models\sbbolxml\response\BigFilesStatusType[] $bigFilesStatuses
     * @return static
     */
    public function setBigFilesStatuses(array $bigFilesStatuses)
    {
        $this->bigFilesStatuses = $bigFilesStatuses;
        return $this;
    }

    /**
     * Gets as genericLetterToBankTicket
     *
     * Дополнительная информация по ПСФ
     *
     * @return \common\models\sbbolxml\response\GenericLetterToBankTicketType
     */
    public function getGenericLetterToBankTicket()
    {
        return $this->genericLetterToBankTicket;
    }

    /**
     * Sets a new genericLetterToBankTicket
     *
     * Дополнительная информация по ПСФ
     *
     * @param \common\models\sbbolxml\response\GenericLetterToBankTicketType $genericLetterToBankTicket
     * @return static
     */
    public function setGenericLetterToBankTicket(\common\models\sbbolxml\response\GenericLetterToBankTicketType $genericLetterToBankTicket)
    {
        $this->genericLetterToBankTicket = $genericLetterToBankTicket;
        return $this;
    }


}

