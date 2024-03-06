<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing BlockedFullInfoType
 *
 *
 * XSD Type: BlockedFullInfo
 */
class BlockedFullInfoType
{

    /**
     * Блокировки по дебету
     *
     * @property \common\models\sbbolxml\response\DebetFullType $debet
     */
    private $debet = null;

    /**
     * Блокировки по кредиту
     *
     * @property \common\models\sbbolxml\response\CredFullType $cred
     */
    private $cred = null;

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

    /**
     * Gets as cred
     *
     * Блокировки по кредиту
     *
     * @return \common\models\sbbolxml\response\CredFullType
     */
    public function getCred()
    {
        return $this->cred;
    }

    /**
     * Sets a new cred
     *
     * Блокировки по кредиту
     *
     * @param \common\models\sbbolxml\response\CredFullType $cred
     * @return static
     */
    public function setCred(\common\models\sbbolxml\response\CredFullType $cred)
    {
        $this->cred = $cred;
        return $this;
    }


}

