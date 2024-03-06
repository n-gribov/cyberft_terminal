<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing DocSum33BRaifType
 *
 *
 * XSD Type: DocSum_33BRaif
 */
class DocSum33BRaifType
{

    /**
     * Сумма списания
     *
     * @property \common\models\raiffeisenxml\request\DocSum33BRaifType\WriteOffSumAType $writeOffSum
     */
    private $writeOffSum = null;

    /**
     * Gets as writeOffSum
     *
     * Сумма списания
     *
     * @return \common\models\raiffeisenxml\request\DocSum33BRaifType\WriteOffSumAType
     */
    public function getWriteOffSum()
    {
        return $this->writeOffSum;
    }

    /**
     * Sets a new writeOffSum
     *
     * Сумма списания
     *
     * @param \common\models\raiffeisenxml\request\DocSum33BRaifType\WriteOffSumAType $writeOffSum
     * @return static
     */
    public function setWriteOffSum(\common\models\raiffeisenxml\request\DocSum33BRaifType\WriteOffSumAType $writeOffSum)
    {
        $this->writeOffSum = $writeOffSum;
        return $this;
    }


}

