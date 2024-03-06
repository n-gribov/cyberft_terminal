<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BFAttachmentType
 *
 * Файл в системе БФ
 * XSD Type: BFAttachment
 */
class BFAttachmentType extends BFUploadAttachmentType
{

    /**
     * Уникальный идентификатор задачи на закачку файла в БФ.
     *
     * @property string $transferJobGUID
     */
    private $transferJobGUID = null;

    /**
     * Gets as transferJobGUID
     *
     * Уникальный идентификатор задачи на закачку файла в БФ.
     *
     * @return string
     */
    public function getTransferJobGUID()
    {
        return $this->transferJobGUID;
    }

    /**
     * Sets a new transferJobGUID
     *
     * Уникальный идентификатор задачи на закачку файла в БФ.
     *
     * @param string $transferJobGUID
     * @return static
     */
    public function setTransferJobGUID($transferJobGUID)
    {
        $this->transferJobGUID = $transferJobGUID;
        return $this;
    }


}

