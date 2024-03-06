<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType;

/**
 * Class representing CredReceiptAType
 */
class CredReceiptAType
{

    /**
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType[] $oper
     */
    private $oper = [
        
    ];

    /**
     * Adds as oper
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType $oper
     */
    public function addToOper(\common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType $oper)
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
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType[]
     */
    public function getOper()
    {
        return $this->oper;
    }

    /**
     * Sets a new oper
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType\HelpInfoAType\CredReceiptAType\OperAType[] $oper
     * @return static
     */
    public function setOper(array $oper)
    {
        $this->oper = $oper;
        return $this;
    }


}

