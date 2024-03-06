<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DealPassCon138IType
 *
 *
 * XSD Type: DealPassCon138I
 */
class DealPassCon138IType extends DocBaseType
{

    /**
     * Общие реквизиты паспорта сделки по контракту 138И
     *
     * @property \common\models\sbbolxml\request\DocDataCCDealPassCon138IType $docData
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
     * 3. Общие сведения о контракте
     *
     * @property \common\models\sbbolxml\request\ComDataType $comData
     */
    private $comData = null;

    /**
     * Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @property string $numPSOtherBank
     */
    private $numPSOtherBank = null;

    /**
     * 7. Справочная информация
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
     * Общие реквизиты паспорта сделки по контракту 138И
     *
     * @return \common\models\sbbolxml\request\DocDataCCDealPassCon138IType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты паспорта сделки по контракту 138И
     *
     * @param \common\models\sbbolxml\request\DocDataCCDealPassCon138IType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataCCDealPassCon138IType $docData)
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
     * Gets as comData
     *
     * 3. Общие сведения о контракте
     *
     * @return \common\models\sbbolxml\request\ComDataType
     */
    public function getComData()
    {
        return $this->comData;
    }

    /**
     * Sets a new comData
     *
     * 3. Общие сведения о контракте
     *
     * @param \common\models\sbbolxml\request\ComDataType $comData
     * @return static
     */
    public function setComData(\common\models\sbbolxml\request\ComDataType $comData)
    {
        $this->comData = $comData;
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
     * Gets as suppleInfo
     *
     * 7. Справочная информация
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
     * 7. Справочная информация
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

