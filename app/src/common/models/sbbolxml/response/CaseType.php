<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CaseType
 *
 *
 * XSD Type: CaseType
 */
class CaseType
{

    /**
     * Код причины отказа
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Комментарий
     *
     * @property string $comment
     */
    private $comment = null;

    /**
     * Gets as code
     *
     * Код причины отказа
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
     * Код причины отказа
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
     * Gets as comment
     *
     * Комментарий
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets a new comment
     *
     * Комментарий
     *
     * @param string $comment
     * @return static
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }


}

