<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DealPassCred138IType
 *
 *
 * XSD Type: DealPassCred138I
 */
class DealPassCred138IType extends DocBaseType
{

    /**
     * Общие реквизиты паспорта сделки по кредиту 138И
     *
     * @property \common\models\sbbolxml\request\DocDataCCDealPassCred138IType $docData
     */
    private $docData = null;

    /**
     * Сведения о резиденте
     *
     * @property \common\models\sbbolxml\request\ResInfoType $resInfo
     */
    private $resInfo = null;

    /**
     * Реквизиты иностранного контрагента
     *
     * @property \common\models\sbbolxml\request\DealPassBeneficiarType[] $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * Общие сведения о кредитном договоре
     *
     * @property \common\models\sbbolxml\request\ComDataCredType $comDataCred
     */
    private $comDataCred = null;

    /**
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @property \common\models\sbbolxml\request\TrancheOperType[] $trancheInfo
     */
    private $trancheInfo = null;

    /**
     * Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @property string $numPSOtherBank
     */
    private $numPSOtherBank = null;

    /**
     * Специальные сведения о кредитном договоре
     *
     * @property \common\models\sbbolxml\request\CredSpecDataType $specData
     */
    private $specData = null;

    /**
     * Справочная информация о кредитном договоре
     *
     * @property \common\models\sbbolxml\request\CredHelpInfoType $helpInfo
     */
    private $helpInfo = null;

    /**
     * Справочная информация
     *
     * @property \common\models\sbbolxml\request\SuppleInfoType $suppleInfo
     */
    private $suppleInfo = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

    /**
     * Данные о больших файлах, связанных с сущностью
     *
     * @property \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     */
    private $bigFileAttachments = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты паспорта сделки по кредиту 138И
     *
     * @return \common\models\sbbolxml\request\DocDataCCDealPassCred138IType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты паспорта сделки по кредиту 138И
     *
     * @param \common\models\sbbolxml\request\DocDataCCDealPassCred138IType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataCCDealPassCred138IType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as resInfo
     *
     * Сведения о резиденте
     *
     * @return \common\models\sbbolxml\request\ResInfoType
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
     * @param \common\models\sbbolxml\request\ResInfoType $resInfo
     * @return static
     */
    public function setResInfo(\common\models\sbbolxml\request\ResInfoType $resInfo)
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
     * Gets as comDataCred
     *
     * Общие сведения о кредитном договоре
     *
     * @return \common\models\sbbolxml\request\ComDataCredType
     */
    public function getComDataCred()
    {
        return $this->comDataCred;
    }

    /**
     * Sets a new comDataCred
     *
     * Общие сведения о кредитном договоре
     *
     * @param \common\models\sbbolxml\request\ComDataCredType $comDataCred
     * @return static
     */
    public function setComDataCred(\common\models\sbbolxml\request\ComDataCredType $comDataCred)
    {
        $this->comDataCred = $comDataCred;
        return $this;
    }

    /**
     * Adds as oper
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @return static
     * @param \common\models\sbbolxml\request\TrancheOperType $oper
     */
    public function addToTrancheInfo(\common\models\sbbolxml\request\TrancheOperType $oper)
    {
        $this->trancheInfo[] = $oper;
        return $this;
    }

    /**
     * isset trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
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
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
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
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @return \common\models\sbbolxml\request\TrancheOperType[]
     */
    public function getTrancheInfo()
    {
        return $this->trancheInfo;
    }

    /**
     * Sets a new trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному договору
     *
     * @param \common\models\sbbolxml\request\TrancheOperType[] $trancheInfo
     * @return static
     */
    public function setTrancheInfo(array $trancheInfo)
    {
        $this->trancheInfo = $trancheInfo;
        return $this;
    }

    /**
     * Gets as numPSOtherBank
     *
     * Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @return string
     */
    public function getNumPSOtherBank()
    {
        return $this->numPSOtherBank;
    }

    /**
     * Sets a new numPSOtherBank
     *
     * Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @param string $numPSOtherBank
     * @return static
     */
    public function setNumPSOtherBank($numPSOtherBank)
    {
        $this->numPSOtherBank = $numPSOtherBank;
        return $this;
    }

    /**
     * Gets as specData
     *
     * Специальные сведения о кредитном договоре
     *
     * @return \common\models\sbbolxml\request\CredSpecDataType
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
     * @param \common\models\sbbolxml\request\CredSpecDataType $specData
     * @return static
     */
    public function setSpecData(\common\models\sbbolxml\request\CredSpecDataType $specData)
    {
        $this->specData = $specData;
        return $this;
    }

    /**
     * Gets as helpInfo
     *
     * Справочная информация о кредитном договоре
     *
     * @return \common\models\sbbolxml\request\CredHelpInfoType
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
     * @param \common\models\sbbolxml\request\CredHelpInfoType $helpInfo
     * @return static
     */
    public function setHelpInfo(\common\models\sbbolxml\request\CredHelpInfoType $helpInfo)
    {
        $this->helpInfo = $helpInfo;
        return $this;
    }

    /**
     * Gets as suppleInfo
     *
     * Справочная информация
     *
     * @return \common\models\sbbolxml\request\SuppleInfoType
     */
    public function getSuppleInfo()
    {
        return $this->suppleInfo;
    }

    /**
     * Sets a new suppleInfo
     *
     * Справочная информация
     *
     * @param \common\models\sbbolxml\request\SuppleInfoType $suppleInfo
     * @return static
     */
    public function setSuppleInfo(\common\models\sbbolxml\request\SuppleInfoType $suppleInfo)
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

    /**
     * Adds as bigFileAttachment
     *
     * Данные о больших файлах, связанных с сущностью
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
     * Данные о больших файлах, связанных с сущностью
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
     * Данные о больших файлах, связанных с сущностью
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
     * Данные о больших файлах, связанных с сущностью
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
     * Данные о больших файлах, связанных с сущностью
     *
     * @param \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     * @return static
     */
    public function setBigFileAttachments(array $bigFileAttachments)
    {
        $this->bigFileAttachments = $bigFileAttachments;
        return $this;
    }


}

