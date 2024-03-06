<?php

namespace common\models\raiffeisenxml\request\ConfDocCertificate138IRaifType;

/**
 * Class representing TableAType
 */
class TableAType
{

    /**
     * @property \common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType[] $oper
     */
    private $oper = [
        
    ];

    /**
     * Adds as oper
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType $oper
     */
    public function addToOper(\common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType $oper)
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
     * @return \common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType[]
     */
    public function getOper()
    {
        return $this->oper;
    }

    /**
     * Sets a new oper
     *
     * @param \common\models\raiffeisenxml\request\ConfDocCertificateDoc138IRaifType[] $oper
     * @return static
     */
    public function setOper(array $oper)
    {
        $this->oper = $oper;
        return $this;
    }


}

