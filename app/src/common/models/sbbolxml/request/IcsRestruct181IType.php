<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing IcsRestruct181IType
 *
 * Заявление о переоформлении ПС
 * XSD Type: IcsRestruct181I
 */
class IcsRestruct181IType extends DocBaseType
{

    /**
     * Реквизиты документа
     *
     * @property \common\models\sbbolxml\request\DocDataCCICSRestructType $docData
     */
    private $docData = null;

    /**
     * Переофомляемые контракты
     *
     * @property \common\models\sbbolxml\request\RestructICSDataType[] $icsRestruct
     */
    private $icsRestruct = null;

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
     * Реквизиты документа
     *
     * @return \common\models\sbbolxml\request\DocDataCCICSRestructType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Реквизиты документа
     *
     * @param \common\models\sbbolxml\request\DocDataCCICSRestructType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataCCICSRestructType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Adds as iCSData
     *
     * Переофомляемые контракты
     *
     * @return static
     * @param \common\models\sbbolxml\request\RestructICSDataType $iCSData
     */
    public function addToIcsRestruct(\common\models\sbbolxml\request\RestructICSDataType $iCSData)
    {
        $this->icsRestruct[] = $iCSData;
        return $this;
    }

    /**
     * isset icsRestruct
     *
     * Переофомляемые контракты
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetIcsRestruct($index)
    {
        return isset($this->icsRestruct[$index]);
    }

    /**
     * unset icsRestruct
     *
     * Переофомляемые контракты
     *
     * @param scalar $index
     * @return void
     */
    public function unsetIcsRestruct($index)
    {
        unset($this->icsRestruct[$index]);
    }

    /**
     * Gets as icsRestruct
     *
     * Переофомляемые контракты
     *
     * @return \common\models\sbbolxml\request\RestructICSDataType[]
     */
    public function getIcsRestruct()
    {
        return $this->icsRestruct;
    }

    /**
     * Sets a new icsRestruct
     *
     * Переофомляемые контракты
     *
     * @param \common\models\sbbolxml\request\RestructICSDataType[] $icsRestruct
     * @return static
     */
    public function setIcsRestruct(array $icsRestruct)
    {
        $this->icsRestruct = $icsRestruct;
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

