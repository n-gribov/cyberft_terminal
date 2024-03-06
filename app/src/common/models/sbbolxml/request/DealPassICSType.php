<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DealPassICSType
 *
 * Ведомость банковского контроля
 * XSD Type: DealPassICS
 */
class DealPassICSType extends DocBaseType
{

    /**
     * Общие реквизиты
     *
     * @property \common\models\sbbolxml\request\DocDataDealPassICSType $docData
     */
    private $docData = null;

    /**
     * Сведения о резиденте
     *
     * @property \common\models\sbbolxml\request\ResInfoICSType $resInfo
     */
    private $resInfo = null;

    /**
     * Реквизиты иностранного контрагента
     *
     * @property \common\models\sbbolxml\request\DealPassBeneficiarType[] $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * Сведения о кредитном договоре/контракте
     *
     * @property \common\models\sbbolxml\request\ICSDataType $iCSData
     */
    private $iCSData = null;

    /**
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @property \common\models\sbbolxml\request\TrancheOperICSType[] $trancheInfo
     */
    private $trancheInfo = null;

    /**
     * Номер контракта/кредитного договора, ранее оформленного в другом
     *  уполномоченном банке
     *
     * @property string $numOtherBank
     */
    private $numOtherBank = null;

    /**
     * Специальные сведения о кредитном договоре
     *
     * @property \common\models\sbbolxml\request\CredSpecDataICSV1Type $specData
     */
    private $specData = null;

    /**
     * Справочная информация о кредитном договоре
     *
     * @property \common\models\sbbolxml\request\CredHelpInfoICSV1Type $helpInfo
     */
    private $helpInfo = null;

    /**
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
     *
     * @property \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     */
    private $bigFileAttachments = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты
     *
     * @return \common\models\sbbolxml\request\DocDataDealPassICSType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты
     *
     * @param \common\models\sbbolxml\request\DocDataDealPassICSType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataDealPassICSType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as resInfo
     *
     * Сведения о резиденте
     *
     * @return \common\models\sbbolxml\request\ResInfoICSType
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
     * @param \common\models\sbbolxml\request\ResInfoICSType $resInfo
     * @return static
     */
    public function setResInfo(\common\models\sbbolxml\request\ResInfoICSType $resInfo)
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
     * @param \common\models\sbbolxml\request\DealPassBeneficiarType $beneficiar
     */
    public function addToBeneficiarInfo(\common\models\sbbolxml\request\DealPassBeneficiarType $beneficiar)
    {
        $this->beneficiarInfo[] = $beneficiar;
        return $this;
    }

    /**
     * isset beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\request\DealPassBeneficiarType[]
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
     * @param \common\models\sbbolxml\request\DealPassBeneficiarType[] $beneficiarInfo
     * @return static
     */
    public function setBeneficiarInfo(array $beneficiarInfo)
    {
        $this->beneficiarInfo = $beneficiarInfo;
        return $this;
    }

    /**
     * Gets as iCSData
     *
     * Сведения о кредитном договоре/контракте
     *
     * @return \common\models\sbbolxml\request\ICSDataType
     */
    public function getICSData()
    {
        return $this->iCSData;
    }

    /**
     * Sets a new iCSData
     *
     * Сведения о кредитном договоре/контракте
     *
     * @param \common\models\sbbolxml\request\ICSDataType $iCSData
     * @return static
     */
    public function setICSData(\common\models\sbbolxml\request\ICSDataType $iCSData)
    {
        $this->iCSData = $iCSData;
        return $this;
    }

    /**
     * Adds as oper
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @return static
     * @param \common\models\sbbolxml\request\TrancheOperICSType $oper
     */
    public function addToTrancheInfo(\common\models\sbbolxml\request\TrancheOperICSType $oper)
    {
        $this->trancheInfo[] = $oper;
        return $this;
    }

    /**
     * isset trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTrancheInfo($index)
    {
        return isset($this->trancheInfo[$index]);
    }

    /**
     * unset trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTrancheInfo($index)
    {
        unset($this->trancheInfo[$index]);
    }

    /**
     * Gets as trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @return \common\models\sbbolxml\request\TrancheOperICSType[]
     */
    public function getTrancheInfo()
    {
        return $this->trancheInfo;
    }

    /**
     * Sets a new trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @param \common\models\sbbolxml\request\TrancheOperICSType[] $trancheInfo
     * @return static
     */
    public function setTrancheInfo(array $trancheInfo)
    {
        $this->trancheInfo = $trancheInfo;
        return $this;
    }

    /**
     * Gets as numOtherBank
     *
     * Номер контракта/кредитного договора, ранее оформленного в другом
     *  уполномоченном банке
     *
     * @return string
     */
    public function getNumOtherBank()
    {
        return $this->numOtherBank;
    }

    /**
     * Sets a new numOtherBank
     *
     * Номер контракта/кредитного договора, ранее оформленного в другом
     *  уполномоченном банке
     *
     * @param string $numOtherBank
     * @return static
     */
    public function setNumOtherBank($numOtherBank)
    {
        $this->numOtherBank = $numOtherBank;
        return $this;
    }

    /**
     * Gets as specData
     *
     * Специальные сведения о кредитном договоре
     *
     * @return \common\models\sbbolxml\request\CredSpecDataICSV1Type
     */
    public function getSpecData()
    {
        return $this->specData;
    }

    /**
     * Sets a new specData
     *
     * Специальные сведения о кредитном договоре
     *
     * @param \common\models\sbbolxml\request\CredSpecDataICSV1Type $specData
     * @return static
     */
    public function setSpecData(\common\models\sbbolxml\request\CredSpecDataICSV1Type $specData)
    {
        $this->specData = $specData;
        return $this;
    }

    /**
     * Gets as helpInfo
     *
     * Справочная информация о кредитном договоре
     *
     * @return \common\models\sbbolxml\request\CredHelpInfoICSV1Type
     */
    public function getHelpInfo()
    {
        return $this->helpInfo;
    }

    /**
     * Sets a new helpInfo
     *
     * Справочная информация о кредитном договоре
     *
     * @param \common\models\sbbolxml\request\CredHelpInfoICSV1Type $helpInfo
     * @return static
     */
    public function setHelpInfo(\common\models\sbbolxml\request\CredHelpInfoICSV1Type $helpInfo)
    {
        $this->helpInfo = $helpInfo;
        return $this;
    }

    /**
     * Adds as bigFileAttachment
     *
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
     *
     * @return static
     * @param \common\models\sbbolxml\request\BigFileAttachmentType $bigFileAttachment
     */
    public function addToBigFileAttachments(\common\models\sbbolxml\request\BigFileAttachmentType $bigFileAttachment)
    {
        $this->bigFileAttachments[] = $bigFileAttachment;
        return $this;
    }

    /**
     * isset bigFileAttachments
     *
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBigFileAttachments($index)
    {
        return isset($this->bigFileAttachments[$index]);
    }

    /**
     * unset bigFileAttachments
     *
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBigFileAttachments($index)
    {
        unset($this->bigFileAttachments[$index]);
    }

    /**
     * Gets as bigFileAttachments
     *
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
     *
     * @return \common\models\sbbolxml\request\BigFileAttachmentType[]
     */
    public function getBigFileAttachments()
    {
        return $this->bigFileAttachments;
    }

    /**
     * Sets a new bigFileAttachments
     *
     * Приложенные к документу отсканированные образы-вложения - для АС БФ
     *
     * @param \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     * @return static
     */
    public function setBigFileAttachments(array $bigFileAttachments)
    {
        $this->bigFileAttachments = $bigFileAttachments;
        return $this;
    }

    /**
     * Adds as attachment
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @return static
     * @param \common\models\sbbolxml\request\AttachmentsType\AttachmentAType $attachment
     */
    public function addToAttachments(\common\models\sbbolxml\request\AttachmentsType\AttachmentAType $attachment)
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
     * @return \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[]
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
     * @param \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }


}

