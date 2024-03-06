<?php

namespace common\models\sbbolxml\request\BigFilesUploadLinksRequestType;

/**
 * Class representing BigFilesUploadLinkRequestAType
 */
class BigFilesUploadLinkRequestAType
{

    /**
     * Внешний идентификатор сущности
     *
     * @property string $essenceId
     */
    private $essenceId = null;

    /**
     * Тип сущности: SUBST_DOC - подтверждающий документ, ACT - акт, CONTRACT - контракт
     *
     * @property string $essenceType
     */
    private $essenceType = null;

    /**
     * Список запросов на ссылки в БФ
     *
     * @property \common\models\sbbolxml\request\BFUploadAttachmentType[] $bFAttachments
     */
    private $bFAttachments = null;

    /**
     * Gets as essenceId
     *
     * Внешний идентификатор сущности
     *
     * @return string
     */
    public function getEssenceId()
    {
        return $this->essenceId;
    }

    /**
     * Sets a new essenceId
     *
     * Внешний идентификатор сущности
     *
     * @param string $essenceId
     * @return static
     */
    public function setEssenceId($essenceId)
    {
        $this->essenceId = $essenceId;
        return $this;
    }

    /**
     * Gets as essenceType
     *
     * Тип сущности: SUBST_DOC - подтверждающий документ, ACT - акт, CONTRACT - контракт
     *
     * @return string
     */
    public function getEssenceType()
    {
        return $this->essenceType;
    }

    /**
     * Sets a new essenceType
     *
     * Тип сущности: SUBST_DOC - подтверждающий документ, ACT - акт, CONTRACT - контракт
     *
     * @param string $essenceType
     * @return static
     */
    public function setEssenceType($essenceType)
    {
        $this->essenceType = $essenceType;
        return $this;
    }

    /**
     * Adds as bFAttachment
     *
     * Список запросов на ссылки в БФ
     *
     * @return static
     * @param \common\models\sbbolxml\request\BFUploadAttachmentType $bFAttachment
     */
    public function addToBFAttachments(\common\models\sbbolxml\request\BFUploadAttachmentType $bFAttachment)
    {
        $this->bFAttachments[] = $bFAttachment;
        return $this;
    }

    /**
     * isset bFAttachments
     *
     * Список запросов на ссылки в БФ
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBFAttachments($index)
    {
        return isset($this->bFAttachments[$index]);
    }

    /**
     * unset bFAttachments
     *
     * Список запросов на ссылки в БФ
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBFAttachments($index)
    {
        unset($this->bFAttachments[$index]);
    }

    /**
     * Gets as bFAttachments
     *
     * Список запросов на ссылки в БФ
     *
     * @return \common\models\sbbolxml\request\BFUploadAttachmentType[]
     */
    public function getBFAttachments()
    {
        return $this->bFAttachments;
    }

    /**
     * Sets a new bFAttachments
     *
     * Список запросов на ссылки в БФ
     *
     * @param \common\models\sbbolxml\request\BFUploadAttachmentType[] $bFAttachments
     * @return static
     */
    public function setBFAttachments(array $bFAttachments)
    {
        $this->bFAttachments = $bFAttachments;
        return $this;
    }


}

