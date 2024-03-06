<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ActAddType
 *
 * Добавление акта ГОЗ
 * XSD Type: ActAdd
 */
class ActAddType
{

    /**
     * Идентификатор документа в СББОЛ. При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
     *
     * @property string $essenceId
     */
    private $essenceId = null;

    /**
     * @property \common\models\sbbolxml\request\ActAddType\GOZActAType $gOZAct
     */
    private $gOZAct = null;

    /**
     * @property \common\models\sbbolxml\request\GozCustomerType $customer
     */
    private $customer = null;

    /**
     * @property \common\models\sbbolxml\request\GozExecutorType $executor
     */
    private $executor = null;

    /**
     * Ссылки на вложенные файлы
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
     * Gets as gOZAct
     *
     * @return \common\models\sbbolxml\request\ActAddType\GOZActAType
     */
    public function getGOZAct()
    {
        return $this->gOZAct;
    }

    /**
     * Sets a new gOZAct
     *
     * @param \common\models\sbbolxml\request\ActAddType\GOZActAType $gOZAct
     * @return static
     */
    public function setGOZAct(\common\models\sbbolxml\request\ActAddType\GOZActAType $gOZAct)
    {
        $this->gOZAct = $gOZAct;
        return $this;
    }

    /**
     * Gets as customer
     *
     * @return \common\models\sbbolxml\request\GozCustomerType
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Sets a new customer
     *
     * @param \common\models\sbbolxml\request\GozCustomerType $customer
     * @return static
     */
    public function setCustomer(\common\models\sbbolxml\request\GozCustomerType $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Gets as executor
     *
     * @return \common\models\sbbolxml\request\GozExecutorType
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     * Sets a new executor
     *
     * @param \common\models\sbbolxml\request\GozExecutorType $executor
     * @return static
     */
    public function setExecutor(\common\models\sbbolxml\request\GozExecutorType $executor)
    {
        $this->executor = $executor;
        return $this;
    }

    /**
     * Adds as bFAttachment
     *
     * Ссылки на вложенные файлы
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
     * Ссылки на вложенные файлы
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
     * Ссылки на вложенные файлы
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
     * Ссылки на вложенные файлы
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
     * Ссылки на вложенные файлы
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

