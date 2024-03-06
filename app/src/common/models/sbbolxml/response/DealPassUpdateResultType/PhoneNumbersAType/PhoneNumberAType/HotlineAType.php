<?php

namespace common\models\sbbolxml\response\DealPassUpdateResultType\PhoneNumbersAType\PhoneNumberAType;

/**
 * Class representing HotlineAType
 */
class HotlineAType
{

    /**
     * Номер телефона
     *
     * @property string $number
     */
    private $number = null;

    /**
     * Комментарий
     *
     * @property string $comment
     */
    private $comment = null;

    /**
     * Gets as number
     *
     * Номер телефона
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets a new number
     *
     * Номер телефона
     *
     * @param string $number
     * @return static
     */
    public function setNumber($number)
    {
        $this->number = $number;
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

