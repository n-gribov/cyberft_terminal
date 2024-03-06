<?php

namespace common\models\raiffeisenxml\request\CurrConvTransType;

/**
 * Class representing AccTransAType
 */
class AccTransAType
{

    /**
     * @property \common\models\raiffeisenxml\request\AccountRUType $our
     */
    private $our = null;

    /**
     * @property \common\models\raiffeisenxml\request\CurrConvTransType\AccTransAType\OtherAType $other
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
     * @return \common\models\raiffeisenxml\request\CurrConvTransType\AccTransAType\OtherAType
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Sets a new other
     *
     * @param \common\models\raiffeisenxml\request\CurrConvTransType\AccTransAType\OtherAType $other
     * @return static
     */
    public function setOther(\common\models\raiffeisenxml\request\CurrConvTransType\AccTransAType\OtherAType $other)
    {
        $this->other = $other;
        return $this;
    }


}

