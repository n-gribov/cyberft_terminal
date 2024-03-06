<?php

namespace common\models\sbbolxml\response\StatementType;

/**
 * Class representing BlockedInfoAType
 */
class BlockedInfoAType
{

    /**
     * Блокировки по дебету
     *
     * @property \common\models\sbbolxml\response\DebetFullType $debet
     */
    private $debet = null;

    /**
     * Gets as debet
     *
     * Блокировки по дебету
     *
     * @return \common\models\sbbolxml\response\DebetFullType
     */
    public function getDebet()
    {
        return $this->debet;
    }

    /**
     * Sets a new debet
     *
     * Блокировки по дебету
     *
     * @param \common\models\sbbolxml\response\DebetFullType $debet
     * @return static
     */
    public function setDebet(\common\models\sbbolxml\response\DebetFullType $debet)
    {
        $this->debet = $debet;
        return $this;
    }


}

