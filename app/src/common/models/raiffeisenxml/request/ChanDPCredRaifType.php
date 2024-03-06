<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ChanDPCredRaifType
 *
 *
 * XSD Type: ChanDPCredRaif
 */
class ChanDPCredRaifType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты документа ВК ДБО
     *
     * @property \common\models\raiffeisenxml\request\DocDataCCPassRaifType $docData
     */
    private $docData = null;

    /**
     * Данные паспортов сделок
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\DealPassportsAType\DealPassAType[] $dealPassports
     */
    private $dealPassports = null;

    /**
     * Сведения о резиденте
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\ResInfoAType $resInfo
     */
    private $resInfo = null;

    /**
     * Реквизиты иностранного контрагента
     *
     * @property \common\models\raiffeisenxml\request\BeneficiarInfoChanDPRaifType[] $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * Общие сведения о кредитном договоре
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\CredDataAType $credData
     */
    private $credData = null;

    /**
     * Особые условия
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType $specTerms
     */
    private $specTerms = null;

    /**
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @property \common\models\raiffeisenxml\request\TrancheInfoType[] $tranchesInfo
     */
    private $tranchesInfo = null;

    /**
     * Сведения для переоформления ПС
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\ChanInfoAType[] $chanInfo
     */
    private $chanInfo = [
        
    ];

    /**
     * Лист 2. Специальные сведения
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType $specData
     */
    private $specData = null;

    /**
     * Прочая информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Срочное оформление ПС
     *
     * @property bool $urgent
     */
    private $urgent = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

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
     * Общие реквизиты документа ВК ДБО
     *
     * @return \common\models\raiffeisenxml\request\DocDataCCPassRaifType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа ВК ДБО
     *
     * @param \common\models\raiffeisenxml\request\DocDataCCPassRaifType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\DocDataCCPassRaifType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as dealPass
     *
     * Данные паспортов сделок
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\DealPassportsAType\DealPassAType $dealPass
     */
    public function addToDealPassports(\common\models\raiffeisenxml\request\ChanDPCredRaifType\DealPassportsAType\DealPassAType $dealPass)
    {
        $this->dealPassports[] = $dealPass;
        return $this;
    }

    /**
     * isset dealPassports
     *
     * Данные паспортов сделок
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDealPassports($index)
    {
        return isset($this->dealPassports[$index]);
    }

    /**
     * unset dealPassports
     *
     * Данные паспортов сделок
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDealPassports($index)
    {
        unset($this->dealPassports[$index]);
    }

    /**
     * Gets as dealPassports
     *
     * Данные паспортов сделок
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\DealPassportsAType\DealPassAType[]
     */
    public function getDealPassports()
    {
        return $this->dealPassports;
    }

    /**
     * Sets a new dealPassports
     *
     * Данные паспортов сделок
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\DealPassportsAType\DealPassAType[] $dealPassports
     * @return static
     */
    public function setDealPassports(array $dealPassports)
    {
        $this->dealPassports = $dealPassports;
        return $this;
    }

    /**
     * Gets as resInfo
     *
     * Сведения о резиденте
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\ResInfoAType
     */
    public function getResInfo()
    {
        return $this->resInfo;
    }

    /**
     * Sets a new resInfo
     *
     * Сведения о резиденте
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\ResInfoAType $resInfo
     * @return static
     */
    public function setResInfo(\common\models\raiffeisenxml\request\ChanDPCredRaifType\ResInfoAType $resInfo)
    {
        $this->resInfo = $resInfo;
        return $this;
    }

    /**
     * Adds as beneficiar
     *
     * Реквизиты иностранного контрагента
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\BeneficiarInfoChanDPRaifType $beneficiar
     */
    public function addToBeneficiarInfo(\common\models\raiffeisenxml\request\BeneficiarInfoChanDPRaifType $beneficiar)
    {
        $this->beneficiarInfo[] = $beneficiar;
        return $this;
    }

    /**
     * isset beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBeneficiarInfo($index)
    {
        return isset($this->beneficiarInfo[$index]);
    }

    /**
     * unset beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBeneficiarInfo($index)
    {
        unset($this->beneficiarInfo[$index]);
    }

    /**
     * Gets as beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @return \common\models\raiffeisenxml\request\BeneficiarInfoChanDPRaifType[]
     */
    public function getBeneficiarInfo()
    {
        return $this->beneficiarInfo;
    }

    /**
     * Sets a new beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @param \common\models\raiffeisenxml\request\BeneficiarInfoChanDPRaifType[] $beneficiarInfo
     * @return static
     */
    public function setBeneficiarInfo(array $beneficiarInfo)
    {
        $this->beneficiarInfo = $beneficiarInfo;
        return $this;
    }

    /**
     * Gets as credData
     *
     * Общие сведения о кредитном договоре
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\CredDataAType
     */
    public function getCredData()
    {
        return $this->credData;
    }

    /**
     * Sets a new credData
     *
     * Общие сведения о кредитном договоре
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\CredDataAType $credData
     * @return static
     */
    public function setCredData(\common\models\raiffeisenxml\request\ChanDPCredRaifType\CredDataAType $credData)
    {
        $this->credData = $credData;
        return $this;
    }

    /**
     * Gets as specTerms
     *
     * Особые условия
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType
     */
    public function getSpecTerms()
    {
        return $this->specTerms;
    }

    /**
     * Sets a new specTerms
     *
     * Особые условия
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType $specTerms
     * @return static
     */
    public function setSpecTerms(\common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecTermsAType $specTerms)
    {
        $this->specTerms = $specTerms;
        return $this;
    }

    /**
     * Adds as trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\TrancheInfoType $trancheInfo
     */
    public function addToTranchesInfo(\common\models\raiffeisenxml\request\TrancheInfoType $trancheInfo)
    {
        $this->tranchesInfo[] = $trancheInfo;
        return $this;
    }

    /**
     * isset tranchesInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTranchesInfo($index)
    {
        return isset($this->tranchesInfo[$index]);
    }

    /**
     * unset tranchesInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTranchesInfo($index)
    {
        unset($this->tranchesInfo[$index]);
    }

    /**
     * Gets as tranchesInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @return \common\models\raiffeisenxml\request\TrancheInfoType[]
     */
    public function getTranchesInfo()
    {
        return $this->tranchesInfo;
    }

    /**
     * Sets a new tranchesInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @param \common\models\raiffeisenxml\request\TrancheInfoType[] $tranchesInfo
     * @return static
     */
    public function setTranchesInfo(array $tranchesInfo)
    {
        $this->tranchesInfo = $tranchesInfo;
        return $this;
    }

    /**
     * Adds as chanInfo
     *
     * Сведения для переоформления ПС
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\ChanInfoAType $chanInfo
     */
    public function addToChanInfo(\common\models\raiffeisenxml\request\ChanDPCredRaifType\ChanInfoAType $chanInfo)
    {
        $this->chanInfo[] = $chanInfo;
        return $this;
    }

    /**
     * isset chanInfo
     *
     * Сведения для переоформления ПС
     *
     * @param int|string $index
     * @return bool
     */
    public function issetChanInfo($index)
    {
        return isset($this->chanInfo[$index]);
    }

    /**
     * unset chanInfo
     *
     * Сведения для переоформления ПС
     *
     * @param int|string $index
     * @return void
     */
    public function unsetChanInfo($index)
    {
        unset($this->chanInfo[$index]);
    }

    /**
     * Gets as chanInfo
     *
     * Сведения для переоформления ПС
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\ChanInfoAType[]
     */
    public function getChanInfo()
    {
        return $this->chanInfo;
    }

    /**
     * Sets a new chanInfo
     *
     * Сведения для переоформления ПС
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\ChanInfoAType[] $chanInfo
     * @return static
     */
    public function setChanInfo(array $chanInfo)
    {
        $this->chanInfo = $chanInfo;
        return $this;
    }

    /**
     * Gets as specData
     *
     * Лист 2. Специальные сведения
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType
     */
    public function getSpecData()
    {
        return $this->specData;
    }

    /**
     * Sets a new specData
     *
     * Лист 2. Специальные сведения
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType $specData
     * @return static
     */
    public function setSpecData(\common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType $specData)
    {
        $this->specData = $specData;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Прочая информация
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
     * Прочая информация
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }

    /**
     * Gets as urgent
     *
     * Срочное оформление ПС
     *
     * @return bool
     */
    public function getUrgent()
    {
        return $this->urgent;
    }

    /**
     * Sets a new urgent
     *
     * Срочное оформление ПС
     *
     * @param bool $urgent
     * @return static
     */
    public function setUrgent($urgent)
    {
        $this->urgent = $urgent;
        return $this;
    }

    /**
     * Adds as attachment
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType $attachment
     */
    public function addToAttachments(\common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * isset attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param int|string $index
     * @return bool
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
     * @param int|string $index
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
     * @return \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType[]
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
     * @param \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }


}

