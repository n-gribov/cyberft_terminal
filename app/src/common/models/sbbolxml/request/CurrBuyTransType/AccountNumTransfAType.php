<?php

namespace common\models\sbbolxml\request\CurrBuyTransType;

use common\models\sbbolxml\request\AccountCurrType;

/**
 * Class representing AccountNumTransfAType
 */
class AccountNumTransfAType extends AccountCurrType
{

    /**
     * 0 - На наш счет в Сбербанке России,
     *  1 - На счет в другом банке/ в других филиалах и отделениях Сбербанка России
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Gets as type
     *
     * 0 - На наш счет в Сбербанке России,
     *  1 - На счет в другом банке/ в других филиалах и отделениях Сбербанка России
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
     * 0 - На наш счет в Сбербанке России,
     *  1 - На счет в другом банке/ в других филиалах и отделениях Сбербанка России
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

