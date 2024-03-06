<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ResponsePartType
 *
 * Часть ответа на запрос
 * XSD Type: ResponsePart
 */
class ResponsePartType
{

    /**
     * @property integer $part
     */
    private $part = null;

    /**
     * Заархивированная часть ответа
     *
     * @property \common\models\sbbolxml\response\AttachmentType $attachment
     */
    private $attachment = null;

    /**
     * Gets as part
     *
     * @return integer
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * Sets a new part
     *
     * @param integer $part
     * @return static
     */
    public function setPart($part)
    {
        $this->part = $part;
        return $this;
    }

    /**
     * Gets as attachment
     *
     * Заархивированная часть ответа
     *
     * @return \common\models\sbbolxml\response\AttachmentType
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Sets a new attachment
     *
     * Заархивированная часть ответа
     *
     * @param \common\models\sbbolxml\response\AttachmentType $attachment
     * @return static
     */
    public function setAttachment(\common\models\sbbolxml\response\AttachmentType $attachment)
    {
        $this->attachment = $attachment;
        return $this;
    }


}

