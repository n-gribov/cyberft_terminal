<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing TrancheOperType
 *
 * Сведения о сумме и сроках привлечения (предоставления) транша
 * XSD Type: TrancheOper
 */
class TrancheOperType
{

    /**
     * Сумма транша
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Код срока привлечения (предоставления) транша
     *
     * @property \common\models\sbbolxml\request\TermBorrowType $code
     */
    private $code = null;

    /**
     * Ожидаемая дата поступления
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Gets as sum
     *
     * Сумма транша
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма транша
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as code
     *
     * Код срока привлечения (предоставления) транша
     *
     * @return \common\models\sbbolxml\request\TermBorrowType
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Код срока привлечения (предоставления) транша
     *
     * @param \common\models\sbbolxml\request\TermBorrowType $code
     * @return static
     */
    public function setCode(\common\models\sbbolxml\request\TermBorrowType $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as date
     *
     * Ожидаемая дата поступления
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Ожидаемая дата поступления
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }


}

