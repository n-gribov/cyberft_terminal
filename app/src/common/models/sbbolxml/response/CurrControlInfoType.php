<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CurrControlInfoType
 *
 * Информация по валютному контролю
 * XSD Type: CurrControlInfo
 */
class CurrControlInfoType
{

    /**
     * Дата принятия/возврата валютным контролем
     *
     * @property \DateTime $valueDate
     */
    private $valueDate = null;

    /**
     * Причины возврата (пункт Инструкции №181-И)
     *
     * @property \common\models\sbbolxml\response\RejectionCausesType $rejectionCauses
     */
    private $rejectionCauses = null;

    /**
     * Номер паспорта сделки (полный)
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @property string $numPsFull
     */
    private $numPsFull = null;

    /**
     * Номер паспорта сделки (по частям)
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @property \common\models\sbbolxml\response\DealPassNumType $numPs
     */
    private $numPs = null;

    /**
     * Сведения о переоформлении паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @property \common\models\sbbolxml\response\DPReissueDataType[] $dPRe
     */
    private $dPRe = null;

    /**
     * Сведения об оформлении, переводе и закрытии паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @property \common\models\sbbolxml\response\DPEndDataType[] $dPEnd
     */
    private $dPEnd = null;

    /**
     * Сведения о результатах внесений изменений в Ведомости банковского контроля по заявлению
     *
     * @property \common\models\sbbolxml\response\ChangeICSInfoDataType[] $changeICSInfo
     */
    private $changeICSInfo = null;

    /**
     * Справочная информация из банка
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @property \common\models\sbbolxml\response\SuppleInfoType $suppleInfo
     */
    private $suppleInfo = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\response\AttachmentType[] $attachments
     */
    private $attachments = null;

    /**
     * Запрос информации ВК
     *
     * @property \common\models\sbbolxml\response\CurrControlInfoRequestType $currControlInfoRequest
     */
    private $currControlInfoRequest = null;

    /**
     * Информация по результату сделки
     *
     * @property \common\models\sbbolxml\response\DealPassUpdateResultType $dealPassUpdateResult
     */
    private $dealPassUpdateResult = null;

    /**
     * Письма для целей ВК в банк
     *
     * @property \common\models\sbbolxml\response\ExchangeMessagesWithBankType $exchangeMessagesWithBank
     */
    private $exchangeMessagesWithBank = null;

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

    /**
     * Gets as rejectionCauses
     *
     * Причины возврата (пункт Инструкции №181-И)
     *
     * @return \common\models\sbbolxml\response\RejectionCausesType
     */
    public function getRejectionCauses()
    {
        return $this->rejectionCauses;
    }

    /**
     * Sets a new rejectionCauses
     *
     * Причины возврата (пункт Инструкции №181-И)
     *
     * @param \common\models\sbbolxml\response\RejectionCausesType $rejectionCauses
     * @return static
     */
    public function setRejectionCauses(\common\models\sbbolxml\response\RejectionCausesType $rejectionCauses)
    {
        $this->rejectionCauses = $rejectionCauses;
        return $this;
    }

    /**
     * Gets as numPsFull
     *
     * Номер паспорта сделки (полный)
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @return string
     */
    public function getNumPsFull()
    {
        return $this->numPsFull;
    }

    /**
     * Sets a new numPsFull
     *
     * Номер паспорта сделки (полный)
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @param string $numPsFull
     * @return static
     */
    public function setNumPsFull($numPsFull)
    {
        $this->numPsFull = $numPsFull;
        return $this;
    }

    /**
     * Gets as numPs
     *
     * Номер паспорта сделки (по частям)
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @return \common\models\sbbolxml\response\DealPassNumType
     */
    public function getNumPs()
    {
        return $this->numPs;
    }

    /**
     * Sets a new numPs
     *
     * Номер паспорта сделки (по частям)
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @param \common\models\sbbolxml\response\DealPassNumType $numPs
     * @return static
     */
    public function setNumPs(\common\models\sbbolxml\response\DealPassNumType $numPs)
    {
        $this->numPs = $numPs;
        return $this;
    }

    /**
     * Adds as dPReissueData
     *
     * Сведения о переоформлении паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @return static
     * @param \common\models\sbbolxml\response\DPReissueDataType $dPReissueData
     */
    public function addToDPRe(\common\models\sbbolxml\response\DPReissueDataType $dPReissueData)
    {
        $this->dPRe[] = $dPReissueData;
        return $this;
    }

    /**
     * isset dPRe
     *
     * Сведения о переоформлении паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDPRe($index)
    {
        return isset($this->dPRe[$index]);
    }

    /**
     * unset dPRe
     *
     * Сведения о переоформлении паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDPRe($index)
    {
        unset($this->dPRe[$index]);
    }

    /**
     * Gets as dPRe
     *
     * Сведения о переоформлении паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @return \common\models\sbbolxml\response\DPReissueDataType[]
     */
    public function getDPRe()
    {
        return $this->dPRe;
    }

    /**
     * Sets a new dPRe
     *
     * Сведения о переоформлении паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @param \common\models\sbbolxml\response\DPReissueDataType[] $dPRe
     * @return static
     */
    public function setDPRe(array $dPRe)
    {
        $this->dPRe = $dPRe;
        return $this;
    }

    /**
     * Adds as dPEndData
     *
     * Сведения об оформлении, переводе и закрытии паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @return static
     * @param \common\models\sbbolxml\response\DPEndDataType $dPEndData
     */
    public function addToDPEnd(\common\models\sbbolxml\response\DPEndDataType $dPEndData)
    {
        $this->dPEnd[] = $dPEndData;
        return $this;
    }

    /**
     * isset dPEnd
     *
     * Сведения об оформлении, переводе и закрытии паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDPEnd($index)
    {
        return isset($this->dPEnd[$index]);
    }

    /**
     * unset dPEnd
     *
     * Сведения об оформлении, переводе и закрытии паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDPEnd($index)
    {
        unset($this->dPEnd[$index]);
    }

    /**
     * Gets as dPEnd
     *
     * Сведения об оформлении, переводе и закрытии паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @return \common\models\sbbolxml\response\DPEndDataType[]
     */
    public function getDPEnd()
    {
        return $this->dPEnd;
    }

    /**
     * Sets a new dPEnd
     *
     * Сведения об оформлении, переводе и закрытии паспортов сделок
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @param \common\models\sbbolxml\response\DPEndDataType[] $dPEnd
     * @return static
     */
    public function setDPEnd(array $dPEnd)
    {
        $this->dPEnd = $dPEnd;
        return $this;
    }

    /**
     * Adds as changeICSInfoData
     *
     * Сведения о результатах внесений изменений в Ведомости банковского контроля по заявлению
     *
     * @return static
     * @param \common\models\sbbolxml\response\ChangeICSInfoDataType $changeICSInfoData
     */
    public function addToChangeICSInfo(\common\models\sbbolxml\response\ChangeICSInfoDataType $changeICSInfoData)
    {
        $this->changeICSInfo[] = $changeICSInfoData;
        return $this;
    }

    /**
     * isset changeICSInfo
     *
     * Сведения о результатах внесений изменений в Ведомости банковского контроля по заявлению
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetChangeICSInfo($index)
    {
        return isset($this->changeICSInfo[$index]);
    }

    /**
     * unset changeICSInfo
     *
     * Сведения о результатах внесений изменений в Ведомости банковского контроля по заявлению
     *
     * @param scalar $index
     * @return void
     */
    public function unsetChangeICSInfo($index)
    {
        unset($this->changeICSInfo[$index]);
    }

    /**
     * Gets as changeICSInfo
     *
     * Сведения о результатах внесений изменений в Ведомости банковского контроля по заявлению
     *
     * @return \common\models\sbbolxml\response\ChangeICSInfoDataType[]
     */
    public function getChangeICSInfo()
    {
        return $this->changeICSInfo;
    }

    /**
     * Sets a new changeICSInfo
     *
     * Сведения о результатах внесений изменений в Ведомости банковского контроля по заявлению
     *
     * @param \common\models\sbbolxml\response\ChangeICSInfoDataType[] $changeICSInfo
     * @return static
     */
    public function setChangeICSInfo(array $changeICSInfo)
    {
        $this->changeICSInfo = $changeICSInfo;
        return $this;
    }

    /**
     * Gets as suppleInfo
     *
     * Справочная информация из банка
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @return \common\models\sbbolxml\response\SuppleInfoType
     */
    public function getSuppleInfo()
    {
        return $this->suppleInfo;
    }

    /**
     * Sets a new suppleInfo
     *
     * Справочная информация из банка
     *  Неактуально при переключении на Инструкцию 181-И
     *
     * @param \common\models\sbbolxml\response\SuppleInfoType $suppleInfo
     * @return static
     */
    public function setSuppleInfo(\common\models\sbbolxml\response\SuppleInfoType $suppleInfo)
    {
        $this->suppleInfo = $suppleInfo;
        return $this;
    }

    /**
     * Adds as attachment
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @return static
     * @param \common\models\sbbolxml\response\AttachmentType $attachment
     */
    public function addToAttachments(\common\models\sbbolxml\response\AttachmentType $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * isset attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAttachments($index)
    {
        return isset($this->attachments[$index]);
    }

    /**
     * unset attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAttachments($index)
    {
        unset($this->attachments[$index]);
    }

    /**
     * Gets as attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @return \common\models\sbbolxml\response\AttachmentType[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Sets a new attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param \common\models\sbbolxml\response\AttachmentType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Gets as currControlInfoRequest
     *
     * Запрос информации ВК
     *
     * @return \common\models\sbbolxml\response\CurrControlInfoRequestType
     */
    public function getCurrControlInfoRequest()
    {
        return $this->currControlInfoRequest;
    }

    /**
     * Sets a new currControlInfoRequest
     *
     * Запрос информации ВК
     *
     * @param \common\models\sbbolxml\response\CurrControlInfoRequestType $currControlInfoRequest
     * @return static
     */
    public function setCurrControlInfoRequest(\common\models\sbbolxml\response\CurrControlInfoRequestType $currControlInfoRequest)
    {
        $this->currControlInfoRequest = $currControlInfoRequest;
        return $this;
    }

    /**
     * Gets as dealPassUpdateResult
     *
     * Информация по результату сделки
     *
     * @return \common\models\sbbolxml\response\DealPassUpdateResultType
     */
    public function getDealPassUpdateResult()
    {
        return $this->dealPassUpdateResult;
    }

    /**
     * Sets a new dealPassUpdateResult
     *
     * Информация по результату сделки
     *
     * @param \common\models\sbbolxml\response\DealPassUpdateResultType $dealPassUpdateResult
     * @return static
     */
    public function setDealPassUpdateResult(\common\models\sbbolxml\response\DealPassUpdateResultType $dealPassUpdateResult)
    {
        $this->dealPassUpdateResult = $dealPassUpdateResult;
        return $this;
    }

    /**
     * Gets as exchangeMessagesWithBank
     *
     * Письма для целей ВК в банк
     *
     * @return \common\models\sbbolxml\response\ExchangeMessagesWithBankType
     */
    public function getExchangeMessagesWithBank()
    {
        return $this->exchangeMessagesWithBank;
    }

    /**
     * Sets a new exchangeMessagesWithBank
     *
     * Письма для целей ВК в банк
     *
     * @param \common\models\sbbolxml\response\ExchangeMessagesWithBankType $exchangeMessagesWithBank
     * @return static
     */
    public function setExchangeMessagesWithBank(\common\models\sbbolxml\response\ExchangeMessagesWithBankType $exchangeMessagesWithBank)
    {
        $this->exchangeMessagesWithBank = $exchangeMessagesWithBank;
        return $this;
    }


}

