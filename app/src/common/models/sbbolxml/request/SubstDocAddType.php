<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SubstDocAddType
 *
 *
 * XSD Type: SubstDocAdd
 */
class SubstDocAddType
{

    /**
     * Идентификатор документа в СББОЛ. При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
     *
     * @property string $essenceId
     */
    private $essenceId = null;

    /**
     * Дата документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * @property string $substDocType
     */
    private $substDocType = null;

    /**
     * Пользовательское наименование документа
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Номер отдельного счета
     *
     * @property string $accountNumber
     */
    private $accountNumber = null;

    /**
     * Список запросов на ссылки в БФ
     *
     * @property \common\models\sbbolxml\request\BFAttachmentType[] $bFAttachments
     */
    private $bFAttachments = null;

    /**
     * Gets as essenceId
     *
     * Идентификатор документа в СББОЛ. При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
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
     * Идентификатор документа в СББОЛ. При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
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
     * Gets as docDate
     *
     * Дата документа
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата документа
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер документа
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер документа
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as substDocType
     *
     * @return string
     */
    public function getSubstDocType()
    {
        return $this->substDocType;
    }

    /**
     * Sets a new substDocType
     *
     * @param string $substDocType
     * @return static
     */
    public function setSubstDocType($substDocType)
    {
        $this->substDocType = $substDocType;
        return $this;
    }

    /**
     * Gets as name
     *
     * Пользовательское наименование документа
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Пользовательское наименование документа
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as accountNumber
     *
     * Номер отдельного счета
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Sets a new accountNumber
     *
     * Номер отдельного счета
     *
     * @param string $accountNumber
     * @return static
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    /**
     * Adds as bFAttachment
     *
     * Список запросов на ссылки в БФ
     *
     * @return static
     * @param \common\models\sbbolxml\request\BFAttachmentType $bFAttachment
     */
    public function addToBFAttachments(\common\models\sbbolxml\request\BFAttachmentType $bFAttachment)
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
     * @return \common\models\sbbolxml\request\BFAttachmentType[]
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
     * @param \common\models\sbbolxml\request\BFAttachmentType[] $bFAttachments
     * @return static
     */
    public function setBFAttachments(array $bFAttachments)
    {
        $this->bFAttachments = $bFAttachments;
        return $this;
    }


}

