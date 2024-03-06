<?php

namespace common\models\sbbolxml\response\ResponseType\NewsAType;

/**
 * Class representing NewAType
 */
class NewAType
{

    /**
     * Идентификатор новости
     *
     * @property string $id
     */
    private $id = null;

    /**
     * Дата и время новости
     *
     * @property \DateTime $createTime
     */
    private $createTime = null;

    /**
     * Видимость: 1-отображать новость; 0- не отображать
     *  новость.
     *
     * @property boolean $visible
     */
    private $visible = null;

    /**
     * Текст новости
     *
     * @property string $textData
     */
    private $textData = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as id
     *
     * Идентификатор новости
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets a new id
     *
     * Идентификатор новости
     *
     * @param string $id
     * @return static
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets as createTime
     *
     * Дата и время новости
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
     * Дата и время новости
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
     * Gets as visible
     *
     * Видимость: 1-отображать новость; 0- не отображать
     *  новость.
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Sets a new visible
     *
     * Видимость: 1-отображать новость; 0- не отображать
     *  новость.
     *
     * @param boolean $visible
     * @return static
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * Gets as textData
     *
     * Текст новости
     *
     * @return string
     */
    public function getTextData()
    {
        return $this->textData;
    }

    /**
     * Sets a new textData
     *
     * Текст новости
     *
     * @param string $textData
     * @return static
     */
    public function setTextData($textData)
    {
        $this->textData = $textData;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

