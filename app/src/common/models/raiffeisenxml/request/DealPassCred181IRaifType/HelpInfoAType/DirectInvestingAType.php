<?php

namespace common\models\raiffeisenxml\request\DealPassCred181IRaifType\HelpInfoAType;

/**
 * Class representing DirectInvestingAType
 */
class DirectInvestingAType
{

    /**
     * 0 - не отмечено
     *
     * @property bool $check
     */
    private $check = null;

    /**
     * Gets as check
     *
     * 0 - не отмечено
     *
     * @return bool
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * Sets a new check
     *
     * 0 - не отмечено
     *
     * @param bool $check
     * @return static
     */
    public function setCheck($check)
    {
        $this->check = $check;
        return $this;
    }


}

