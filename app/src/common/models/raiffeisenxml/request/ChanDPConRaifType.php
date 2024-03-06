<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ChanDPConRaifType
 *
 *
 * XSD Type: ChanDPConRaif
 */
class ChanDPConRaifType
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
     * @property \common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType[] $dealPassports
     */
    private $dealPassports = null;

    /**
     * Сведения о резиденте
     *
     * @property \common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType $resInfo
     */
    private $resInfo = null;

    /**
     * Реквизиты иностранного контрагента
     *
     * @property \common\models\raiffeisenxml\request\BeneficiarInfoChanDPRaifType[] $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * Общие сведения о контракте
     *
     * @property \common\models\raiffeisenxml\request\ChanDPConRaifType\ComDataAType $comData
     */
    private $comData = null;

    /**
     * Сведения для переоформления ПС
     *
     * @property \common\models\raiffeisenxml\request\ChanDPConRaifType\ChanInfoAType[] $chanInfo
     */
    private $chanInfo = [
        
    ];

    /**
     * Доп. информация
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
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType $dealPass
     */
    public function addToDealPassports(\common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType $dealPass)
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
     * @return \common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType[]
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
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\DealPassportsAType\DealPassAType[] $dealPassports
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
     * @return \common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType
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
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType $resInfo
     * @return static
     */
    public function setResInfo(\common\models\raiffeisenxml\request\ChanDPConRaifType\ResInfoAType $resInfo)
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
     * Gets as comData
     *
     * Общие сведения о контракте
     *
     * @return \common\models\raiffeisenxml\request\ChanDPConRaifType\ComDataAType
     */
    public function getComData()
    {
        return $this->comData;
    }

    /**
     * Sets a new comData
     *
     * Общие сведения о контракте
     *
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\ComDataAType $comData
     * @return static
     */
    public function setComData(\common\models\raiffeisenxml\request\ChanDPConRaifType\ComDataAType $comData)
    {
        $this->comData = $comData;
        return $this;
    }

    /**
     * Adds as chanInfo
     *
     * Сведения для переоформления ПС
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\ChanInfoAType $chanInfo
     */
    public function addToChanInfo(\common\models\raiffeisenxml\request\ChanDPConRaifType\ChanInfoAType $chanInfo)
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
     * @return \common\models\raiffeisenxml\request\ChanDPConRaifType\ChanInfoAType[]
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
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType\ChanInfoAType[] $chanInfo
     * @return static
     */
    public function setChanInfo(array $chanInfo)
    {
        $this->chanInfo = $chanInfo;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Доп. информация
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
     * Доп. информация
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

