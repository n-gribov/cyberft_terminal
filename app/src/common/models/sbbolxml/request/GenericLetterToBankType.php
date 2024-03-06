<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing GenericLetterToBankType
 *
 * Письмо свободного формата в банк
 * XSD Type: GenericLetterToBank
 */
class GenericLetterToBankType extends DocBaseType
{

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\sbbolxml\request\GenLetDocDataType $docData
     */
    private $docData = null;

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\sbbolxml\request\UserDataType $userData
     */
    private $userData = null;

    /**
     * Тип
     *
     * @property string $pSFType
     */
    private $pSFType = null;

    /**
     * Системное имя типа ПСФ
     *
     * @property string $pSFTypeSystemName
     */
    private $pSFTypeSystemName = null;

    /**
     * Текст сообщения
     *
     * @property string $text
     */
    private $text = null;

    /**
     * Канал поступления
     *
     * @property string $channel
     */
    private $channel = null;

    /**
     * Данные о больших файлах, связанных с сущностью
     *
     * @property \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     */
    private $bigFileAttachments = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\sbbolxml\request\GenLetDocDataType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @param \common\models\sbbolxml\request\GenLetDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\GenLetDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as userData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\sbbolxml\request\UserDataType
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * Sets a new userData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @param \common\models\sbbolxml\request\UserDataType $userData
     * @return static
     */
    public function setUserData(\common\models\sbbolxml\request\UserDataType $userData)
    {
        $this->userData = $userData;
        return $this;
    }

    /**
     * Gets as pSFType
     *
     * Тип
     *
     * @return string
     */
    public function getPSFType()
    {
        return $this->pSFType;
    }

    /**
     * Sets a new pSFType
     *
     * Тип
     *
     * @param string $pSFType
     * @return static
     */
    public function setPSFType($pSFType)
    {
        $this->pSFType = $pSFType;
        return $this;
    }

    /**
     * Gets as pSFTypeSystemName
     *
     * Системное имя типа ПСФ
     *
     * @return string
     */
    public function getPSFTypeSystemName()
    {
        return $this->pSFTypeSystemName;
    }

    /**
     * Sets a new pSFTypeSystemName
     *
     * Системное имя типа ПСФ
     *
     * @param string $pSFTypeSystemName
     * @return static
     */
    public function setPSFTypeSystemName($pSFTypeSystemName)
    {
        $this->pSFTypeSystemName = $pSFTypeSystemName;
        return $this;
    }

    /**
     * Gets as text
     *
     * Текст сообщения
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets a new text
     *
     * Текст сообщения
     *
     * @param string $text
     * @return static
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Gets as channel
     *
     * Канал поступления
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Sets a new channel
     *
     * Канал поступления
     *
     * @param string $channel
     * @return static
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
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

