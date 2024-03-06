<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing TicketType
 *
 *
 * XSD Type: Ticket
 */
class TicketType
{

    /**
     * Тикет СББОЛ (UUID документа)
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
     * Сообщение Клиенту
     *
     * @property string $message
     */
    private $message = null;

    /**
     * Идентификатор смс
     *
     * @property string $smsId
     */
    private $smsId = null;

    /**
     * Для передачи информации по документу из СББОЛ в УС
     *
     * @property \common\models\sbbolxml\response\TicketType\InfoAType $info
     */
    private $info = null;

    /**
     * Параметры произвольного вида
     *
     * @property \common\models\sbbolxml\response\ParamsType\ParamAType[] $otherParams
     */
    private $otherParams = null;

    /**
     * Gets as docId
     *
     * Тикет СББОЛ (UUID документа)
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
     * Тикет СББОЛ (UUID документа)
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
     * Gets as message
     *
     * Сообщение Клиенту
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets a new message
     *
     * Сообщение Клиенту
     *
     * @param string $message
     * @return static
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Gets as smsId
     *
     * Идентификатор смс
     *
     * @return string
     */
    public function getSmsId()
    {
        return $this->smsId;
    }

    /**
     * Sets a new smsId
     *
     * Идентификатор смс
     *
     * @param string $smsId
     * @return static
     */
    public function setSmsId($smsId)
    {
        $this->smsId = $smsId;
        return $this;
    }

    /**
     * Gets as info
     *
     * Для передачи информации по документу из СББОЛ в УС
     *
     * @return \common\models\sbbolxml\response\TicketType\InfoAType
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Sets a new info
     *
     * Для передачи информации по документу из СББОЛ в УС
     *
     * @param \common\models\sbbolxml\response\TicketType\InfoAType $info
     * @return static
     */
    public function setInfo(\common\models\sbbolxml\response\TicketType\InfoAType $info)
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
     * @param \common\models\sbbolxml\response\ParamsType\ParamAType $param
     */
    public function addToOtherParams(\common\models\sbbolxml\response\ParamsType\ParamAType $param)
    {
        $this->otherParams[] = $param;
        return $this;
    }

    /**
     * isset otherParams
     *
     * Параметры произвольного вида
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\ParamsType\ParamAType[]
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
     * @param \common\models\sbbolxml\response\ParamsType\ParamAType[] $otherParams
     * @return static
     */
    public function setOtherParams(array $otherParams)
    {
        $this->otherParams = $otherParams;
        return $this;
    }


}

