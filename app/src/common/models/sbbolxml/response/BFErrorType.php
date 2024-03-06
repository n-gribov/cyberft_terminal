<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing BFErrorType
 *
 *
 * XSD Type: BFError
 */
class BFErrorType
{

    /**
     * Детали ошибки
     *
     * @property string $errorMessage
     */
    private $errorMessage = null;

    /**
     * Gets as errorMessage
     *
     * Детали ошибки
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Sets a new errorMessage
     *
     * Детали ошибки
     *
     * @param string $errorMessage
     * @return static
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }


}

