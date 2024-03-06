<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing TicketClType
 *
 *
 * XSD Type: TicketCl
 */
class TicketClType
{

    /**
     * Тикет (UUID документа)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Дата и время создания квитка (с час. поясами)
     *
     * @property \DateTime $createTime
     */
    private $createTime = null;

    /**
     * @property \common\models\raiffeisenxml\request\TicketClType\InfoAType $info
     */
    private $info = null;

    /**
     * Параметры произвольного вида
     *
     * @property \common\models\raiffeisenxml\request\ParamType[] $otherParams
     */
    private $otherParams = null;

    /**
     * Gets as docId
     *
     * Тикет (UUID документа)
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Тикет (UUID документа)
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as createTime
     *
     * Дата и время создания квитка (с час. поясами)
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Sets a new createTime
     *
     * Дата и время создания квитка (с час. поясами)
     *
     * @param \DateTime $createTime
     * @return static
     */
    public function setCreateTime(\DateTime $createTime)
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * Gets as info
     *
     * @return \common\models\raiffeisenxml\request\TicketClType\InfoAType
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Sets a new info
     *
     * @param \common\models\raiffeisenxml\request\TicketClType\InfoAType $info
     * @return static
     */
    public function setInfo(\common\models\raiffeisenxml\request\TicketClType\InfoAType $info)
    {
        $this->info = $info;
        return $this;
    }

    /**
     * Adds as param
     *
     * Параметры произвольного вида
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ParamType $param
     */
    public function addToOtherParams(\common\models\raiffeisenxml\request\ParamType $param)
    {
        $this->otherParams[] = $param;
        return $this;
    }

    /**
     * isset otherParams
     *
     * Параметры произвольного вида
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOtherParams($index)
    {
        return isset($this->otherParams[$index]);
    }

    /**
     * unset otherParams
     *
     * Параметры произвольного вида
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOtherParams($index)
    {
        unset($this->otherParams[$index]);
    }

    /**
     * Gets as otherParams
     *
     * Параметры произвольного вида
     *
     * @return \common\models\raiffeisenxml\request\ParamType[]
     */
    public function getOtherParams()
    {
        return $this->otherParams;
    }

    /**
     * Sets a new otherParams
     *
     * Параметры произвольного вида
     *
     * @param \common\models\raiffeisenxml\request\ParamType[] $otherParams
     * @return static
     */
    public function setOtherParams(array $otherParams)
    {
        $this->otherParams = $otherParams;
        return $this;
    }


}

