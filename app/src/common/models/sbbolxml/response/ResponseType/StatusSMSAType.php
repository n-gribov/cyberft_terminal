<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing StatusSMSAType
 */
class StatusSMSAType
{

    /**
     * 0 - не выслали
     *  1 - успех, выслали
     *
     * @property boolean $status
     */
    private $status = null;

    /**
     * В платежке ошибки (по идее не должно быть)
     *
     * @property string $exeption
     */
    private $exeption = null;

    /**
     * Gets as status
     *
     * 0 - не выслали
     *  1 - успех, выслали
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * 0 - не выслали
     *  1 - успех, выслали
     *
     * @param boolean $status
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Gets as exeption
     *
     * В платежке ошибки (по идее не должно быть)
     *
     * @return string
     */
    public function getExeption()
    {
        return $this->exeption;
    }

    /**
     * Sets a new exeption
     *
     * В платежке ошибки (по идее не должно быть)
     *
     * @param string $exeption
     * @return static
     */
    public function setExeption($exeption)
    {
        $this->exeption = $exeption;
        return $this;
    }


}

