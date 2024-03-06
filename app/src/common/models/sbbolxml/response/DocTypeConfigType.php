<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DocTypeConfigType
 *
 *
 * XSD Type: DocTypeConfig
 */
class DocTypeConfigType
{

    /**
     * Сервис типа документа
     *
     * @property string $serviceId
     */
    private $serviceId = null;

    /**
     * Комментарий
     *
     * @property integer $attachLimitedLength
     */
    private $attachLimitedLength = null;

    /**
     * Комментарий
     *
     * @property integer $attachLimitedNumber
     */
    private $attachLimitedNumber = null;

    /**
     * Gets as serviceId
     *
     * Сервис типа документа
     *
     * @return string
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Sets a new serviceId
     *
     * Сервис типа документа
     *
     * @param string $serviceId
     * @return static
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    /**
     * Gets as attachLimitedLength
     *
     * Комментарий
     *
     * @return integer
     */
    public function getAttachLimitedLength()
    {
        return $this->attachLimitedLength;
    }

    /**
     * Sets a new attachLimitedLength
     *
     * Комментарий
     *
     * @param integer $attachLimitedLength
     * @return static
     */
    public function setAttachLimitedLength($attachLimitedLength)
    {
        $this->attachLimitedLength = $attachLimitedLength;
        return $this;
    }

    /**
     * Gets as attachLimitedNumber
     *
     * Комментарий
     *
     * @return integer
     */
    public function getAttachLimitedNumber()
    {
        return $this->attachLimitedNumber;
    }

    /**
     * Sets a new attachLimitedNumber
     *
     * Комментарий
     *
     * @param integer $attachLimitedNumber
     * @return static
     */
    public function setAttachLimitedNumber($attachLimitedNumber)
    {
        $this->attachLimitedNumber = $attachLimitedNumber;
        return $this;
    }


}

