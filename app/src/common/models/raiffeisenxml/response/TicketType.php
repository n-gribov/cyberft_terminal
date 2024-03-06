<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing TicketType
 *
 *
 * XSD Type: Ticket
 */
class TicketType
{

    /**
     * Тикет Correqts (UUID документа)
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
     * Для передачи информации по документу в УС
     *
     * @property \common\models\raiffeisenxml\response\TicketType\InfoAType $info
     */
    private $info = null;

    /**
     * Параметры произвольного вида
     *
     * @property \common\models\raiffeisenxml\response\ParamsType\ParamAType[] $otherParams
     */
    private $otherParams = null;

    /**
     * @property \common\models\raiffeisenxml\response\DigitalSignType[] $signs
     */
    private $signs = null;

    /**
     * Gets as docId
     *
     * Тикет Correqts (UUID документа)
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
     * Тикет Correqts (UUID документа)
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
     * Для передачи информации по документу в УС
     *
     * @return \common\models\raiffeisenxml\response\TicketType\InfoAType
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Sets a new info
     *
     * Для передачи информации по документу в УС
     *
     * @param \common\models\raiffeisenxml\response\TicketType\InfoAType $info
     * @return static
     */
    public function setInfo(\common\models\raiffeisenxml\response\TicketType\InfoAType $info)
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
     * @param \common\models\raiffeisenxml\response\ParamsType\ParamAType $param
     */
    public function addToOtherParams(\common\models\raiffeisenxml\response\ParamsType\ParamAType $param)
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
     * @return \common\models\raiffeisenxml\response\ParamsType\ParamAType[]
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
     * @param \common\models\raiffeisenxml\response\ParamsType\ParamAType[] $otherParams
     * @return static
     */
    public function setOtherParams(array $otherParams)
    {
        $this->otherParams = $otherParams;
        return $this;
    }

    /**
     * Adds as sign
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DigitalSignType $sign
     */
    public function addToSigns(\common\models\raiffeisenxml\response\DigitalSignType $sign)
    {
        $this->signs[] = $sign;
        return $this;
    }

    /**
     * isset signs
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSigns($index)
    {
        return isset($this->signs[$index]);
    }

    /**
     * unset signs
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSigns($index)
    {
        unset($this->signs[$index]);
    }

    /**
     * Gets as signs
     *
     * @return \common\models\raiffeisenxml\response\DigitalSignType[]
     */
    public function getSigns()
    {
        return $this->signs;
    }

    /**
     * Sets a new signs
     *
     * @param \common\models\raiffeisenxml\response\DigitalSignType[] $signs
     * @return static
     */
    public function setSigns(array $signs)
    {
        $this->signs = $signs;
        return $this;
    }


}

