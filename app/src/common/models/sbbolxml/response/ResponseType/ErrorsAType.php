<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing ErrorsAType
 */
class ErrorsAType
{

    /**
     * Описание ошибок
     *
     * @property \common\models\sbbolxml\response\ErrorType[] $error
     */
    private $error = array(
        
    );

    /**
     * Adds as error
     *
     * Описание ошибок
     *
     * @return static
     * @param \common\models\sbbolxml\response\ErrorType $error
     */
    public function addToError(\common\models\sbbolxml\response\ErrorType $error)
    {
        $this->error[] = $error;
        return $this;
    }

    /**
     * isset error
     *
     * Описание ошибок
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\ErrorType[]
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
     * @param \common\models\sbbolxml\response\ErrorType[] $error
     * @return static
     */
    public function setError(array $error)
    {
        $this->error = $error;
        return $this;
    }


}

