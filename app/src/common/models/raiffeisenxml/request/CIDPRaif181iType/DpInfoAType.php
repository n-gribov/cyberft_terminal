<?php

namespace common\models\raiffeisenxml\request\CIDPRaif181iType;

/**
 * Class representing DpInfoAType
 */
class DpInfoAType
{

    /**
     * @property \common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType\OperAType[] $oper
     */
    private $oper = [
        
    ];

    /**
     * Пункт инструкции
     *
     * @property string $reason
     */
    private $reason = null;

    /**
     * Сведения о резиденте,которому уступаются требования/на которого переводится долг
     *
     * @property \common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType\TransfConAType $transfCon
     */
    private $transfCon = null;

    /**
     * Adds as oper
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType\OperAType $oper
     */
    public function addToOper(\common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType\OperAType $oper)
    {
        $this->oper[] = $oper;
        return $this;
    }

    /**
     * isset oper
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOper($index)
    {
        return isset($this->oper[$index]);
    }

    /**
     * unset oper
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOper($index)
    {
        unset($this->oper[$index]);
    }

    /**
     * Gets as oper
     *
     * @return \common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType\OperAType[]
     */
    public function getOper()
    {
        return $this->oper;
    }

    /**
     * Sets a new oper
     *
     * @param \common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType\OperAType[] $oper
     * @return static
     */
    public function setOper(array $oper)
    {
        $this->oper = $oper;
        return $this;
    }

    /**
     * Gets as reason
     *
     * Пункт инструкции
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Sets a new reason
     *
     * Пункт инструкции
     *
     * @param string $reason
     * @return static
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Gets as transfCon
     *
     * Сведения о резиденте,которому уступаются требования/на которого переводится долг
     *
     * @return \common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType\TransfConAType
     */
    public function getTransfCon()
    {
        return $this->transfCon;
    }

    /**
     * Sets a new transfCon
     *
     * Сведения о резиденте,которому уступаются требования/на которого переводится долг
     *
     * @param \common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType\TransfConAType $transfCon
     * @return static
     */
    public function setTransfCon(\common\models\raiffeisenxml\request\CIDPRaif181iType\DpInfoAType\TransfConAType $transfCon)
    {
        $this->transfCon = $transfCon;
        return $this;
    }


}

