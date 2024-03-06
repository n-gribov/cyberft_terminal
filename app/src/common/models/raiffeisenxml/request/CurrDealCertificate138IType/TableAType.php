<?php

namespace common\models\raiffeisenxml\request\CurrDealCertificate138IType;

/**
 * Class representing TableAType
 */
class TableAType
{

    /**
     * @property \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType[] $oper
     */
    private $oper = [
        
    ];

    /**
     * Adds as oper
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType $oper
     */
    public function addToOper(\common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType $oper)
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
     * @return \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType[]
     */
    public function getOper()
    {
        return $this->oper;
    }

    /**
     * Sets a new oper
     *
     * @param \common\models\raiffeisenxml\request\CurrDealCertificateDoc138IType[] $oper
     * @return static
     */
    public function setOper(array $oper)
    {
        $this->oper = $oper;
        return $this;
    }


}

