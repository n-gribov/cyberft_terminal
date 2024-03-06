<?php

namespace common\models\sbbolxml\request\AttachmentsType;

/**
 * Class representing AttachmentAType
 */
class AttachmentAType
{

    /**
     * Тип файла вложения
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Имя файла вложения
     *
     * @property string $attachmentName
     */
    private $attachmentName = null;

    /**
     * Пользовательское описание файла вложения
     *
     * @property string $description
     */
    private $description = null;

    /**
     * Дата добавления вложения
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Размер вложения
     *
     * @property integer $size
     */
    private $size = null;

    /**
     * В бинарном представлении в сжатом и несжатом виде
     *
     * @property string $body
     */
    private $body = null;

    /**
     * Подпись вложения
     *
     * @property \common\models\sbbolxml\request\DigitalSignType[] $attachmentSign
     */
    private $attachmentSign = null;

    /**
     * Gets as type
     *
     * Тип файла вложения
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип файла вложения
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as attachmentName
     *
     * Имя файла вложения
     *
     * @return string
     */
    public function getAttachmentName()
    {
        return $this->attachmentName;
    }

    /**
     * Sets a new attachmentName
     *
     * Имя файла вложения
     *
     * @param string $attachmentName
     * @return static
     */
    public function setAttachmentName($attachmentName)
    {
        $this->attachmentName = $attachmentName;
        return $this;
    }

    /**
     * Gets as description
     *
     * Пользовательское описание файла вложения
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * Пользовательское описание файла вложения
     *
     * @param string $description
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата добавления вложения
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата добавления вложения
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as size
     *
     * Размер вложения
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Sets a new size
     *
     * Размер вложения
     *
     * @param integer $size
     * @return static
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Gets as body
     *
     * В бинарном представлении в сжатом и несжатом виде
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets a new body
     *
     * В бинарном представлении в сжатом и несжатом виде
     *
     * @param string $body
     * @return static
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Adds as sign
     *
     * Подпись вложения
     *
     * @return static
     * @param \common\models\sbbolxml\request\DigitalSignType $sign
     */
    public function addToAttachmentSign(\common\models\sbbolxml\request\DigitalSignType $sign)
    {
        $this->attachmentSign[] = $sign;
        return $this;
    }

    /**
     * isset attachmentSign
     *
     * Подпись вложения
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAttachmentSign($index)
    {
        return isset($this->attachmentSign[$index]);
    }

    /**
     * unset attachmentSign
     *
     * Подпись вложения
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAttachmentSign($index)
    {
        unset($this->attachmentSign[$index]);
    }

    /**
     * Gets as attachmentSign
     *
     * Подпись вложения
     *
     * @return \common\models\sbbolxml\request\DigitalSignType[]
     */
    public function getAttachmentSign()
    {
        return $this->attachmentSign;
    }

    /**
     * Sets a new attachmentSign
     *
     * Подпись вложения
     *
     * @param \common\models\sbbolxml\request\DigitalSignType[] $attachmentSign
     * @return static
     */
    public function setAttachmentSign(array $attachmentSign)
    {
        $this->attachmentSign = $attachmentSign;
        return $this;
    }


}

