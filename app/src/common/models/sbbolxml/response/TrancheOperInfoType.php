<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing TrancheOperInfoType
 *
 * Информация об операциях
 * XSD Type: TrancheOperInfo
 */
class TrancheOperInfoType
{

    /**
     * Сумма транша
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Код срока привлечения транша
     *
     * @property \common\models\sbbolxml\response\TrancheOperCodeType $code
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
     * @return \common\models\sbbolxml\response\CurrAmountType
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
     * @param \common\models\sbbolxml\response\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\response\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as code
     *
     * Код срока привлечения транша
     *
     * @return \common\models\sbbolxml\response\TrancheOperCodeType
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Код срока привлечения транша
     *
     * @param \common\models\sbbolxml\response\TrancheOperCodeType $code
     * @return static
     */
    public function setCode(\common\models\sbbolxml\response\TrancheOperCodeType $code)
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

