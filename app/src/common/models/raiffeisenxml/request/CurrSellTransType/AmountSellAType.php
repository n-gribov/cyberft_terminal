<?php

namespace common\models\raiffeisenxml\request\CurrSellTransType;

use common\models\raiffeisenxml\request\CurrAmountType;

/**
 * Class representing AmountSellAType
 */
class AmountSellAType extends CurrAmountType
{

    /**
     * @property \common\models\raiffeisenxml\request\AccountRUType $our
     */
    private $our = null;

    /**
     * @property \common\models\raiffeisenxml\request\AccountRUType $other
     */
    private $other = null;

    /**
     * Gets as our
     *
     * @return \common\models\raiffeisenxml\request\AccountRUType
     */
    public function getOur()
    {
        return $this->our;
    }

    /**
     * Sets a new our
     *
     * @param \common\models\raiffeisenxml\request\AccountRUType $our
     * @return static
     */
    public function setOur(\common\models\raiffeisenxml\request\AccountRUType $our)
    {
        $this->our = $our;
        return $this;
    }

    /**
     * Gets as other
     *
     * @return \common\models\raiffeisenxml\request\AccountRUType
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Sets a new other
     *
     * @param \common\models\raiffeisenxml\request\AccountRUType $other
     * @return static
     */
    public function setOther(\common\models\raiffeisenxml\request\AccountRUType $other)
    {
        $this->other = $other;
        return $this;
    }


}

