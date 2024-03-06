<?php

namespace common\models\raiffeisenxml\response;

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
     * Идентификатор сообщения. Заполнять именем файла, при обработке которого произошла ошибка.
     *
     * @property string $identifier
     */
    private $identifier = null;

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
     * Gets as identifier
     *
     * Идентификатор сообщения. Заполнять именем файла, при обработке которого произошла ошибка.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets a new identifier
     *
     * Идентификатор сообщения. Заполнять именем файла, при обработке которого произошла ошибка.
     *
     * @param string $identifier
     * @return static
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
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

