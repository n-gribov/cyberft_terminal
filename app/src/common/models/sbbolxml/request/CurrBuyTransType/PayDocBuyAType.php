<?php

namespace common\models\sbbolxml\request\CurrBuyTransType;

use common\models\sbbolxml\request\PayDocBuyType;

/**
 * Class representing PayDocBuyAType
 */
class PayDocBuyAType extends PayDocBuyType
{

    /**
     * 0 - Средства в продаваемой валюте списать со счета
     *  1 - Перечислены с нашего счета в Сбербанке Росии
     *  2 - Перечислены со счета в другом банке/ в других филиалах и отделениях Сбербанка России
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Gets as type
     *
     * 0 - Средства в продаваемой валюте списать со счета
     *  1 - Перечислены с нашего счета в Сбербанке Росии
     *  2 - Перечислены со счета в другом банке/ в других филиалах и отделениях Сбербанка России
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
     * 0 - Средства в продаваемой валюте списать со счета
     *  1 - Перечислены с нашего счета в Сбербанке Росии
     *  2 - Перечислены со счета в другом банке/ в других филиалах и отделениях Сбербанка России
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


}

