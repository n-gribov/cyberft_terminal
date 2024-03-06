<?php

namespace common\models\raiffeisenxml\response\ResponseType;

/**
 * Class representing ErrorsAType
 */
class ErrorsAType
{

    /**
     * Описание ошибок
     *
     * @property \common\models\raiffeisenxml\response\ErrorType[] $error
     */
    private $error = [
        
    ];

    /**
     * Adds as error
     *
     * Описание ошибок
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ErrorType $error
     */
    public function addToError(\common\models\raiffeisenxml\response\ErrorType $error)
    {
        $this->error[] = $error;
        return $this;
    }

    /**
     * isset error
     *
     * Описание ошибок
     *
     * @param int|string $index
     * @return bool
     */
    public function issetError($index)
    {
        return isset($this->error[$index]);
    }

    /**
     * unset error
     *
     * Описание ошибок
     *
     * @param int|string $index
     * @return void
     */
    public function unsetError($index)
    {
        unset($this->error[$index]);
    }

    /**
     * Gets as error
     *
     * Описание ошибок
     *
     * @return \common\models\raiffeisenxml\response\ErrorType[]
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets a new error
     *
     * Описание ошибок
     *
     * @param \common\models\raiffeisenxml\response\ErrorType[] $error
     * @return static
     */
    public function setError(array $error)
    {
        $this->error = $error;
        return $this;
    }


}

