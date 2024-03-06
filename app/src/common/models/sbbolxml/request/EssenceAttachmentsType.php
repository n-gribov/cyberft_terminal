<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing EssenceAttachmentsType
 *
 * Данные о файлах реестра задолженностей
 * XSD Type: EssenceAttachments
 */
class EssenceAttachmentsType
{

    /**
     * Данные о файле реестра задолженностей
     *
     * @property \common\models\sbbolxml\request\EssenceAttachmentType $essenceAttachment
     */
    private $essenceAttachment = null;

    /**
     * Gets as essenceAttachment
     *
     * Данные о файле реестра задолженностей
     *
     * @return \common\models\sbbolxml\request\EssenceAttachmentType
     */
    public function getEssenceAttachment()
    {
        return $this->essenceAttachment;
    }

    /**
     * Sets a new essenceAttachment
     *
     * Данные о файле реестра задолженностей
     *
     * @param \common\models\sbbolxml\request\EssenceAttachmentType $essenceAttachment
     * @return static
     */
    public function setEssenceAttachment(\common\models\sbbolxml\request\EssenceAttachmentType $essenceAttachment)
    {
        $this->essenceAttachment = $essenceAttachment;
        return $this;
    }


}

