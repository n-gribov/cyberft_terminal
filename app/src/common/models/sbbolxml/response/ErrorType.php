<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ErrorType
 *
 * Ошибка в заголовке ответа от АБС
 * XSD Type: ErrorType
 */
class ErrorType
{

    /**
     * Код ошибки
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Тип ошибки (Error, Warn, Info)
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Описание ошибки
     *
     * @property string $desc
     */
    private $desc = null;

    /**
     * Gets as code
     *
     * Код ошибки
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Код ошибки
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as type
     *
     * Тип ошибки (Error, Warn, Info)
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
     * Тип ошибки (Error, Warn, Info)
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
     * Gets as desc
     *
     * Описание ошибки
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Sets a new desc
     *
     * Описание ошибки
     *
     * @param string $desc
     * @return static
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
        return $this;
    }


}

