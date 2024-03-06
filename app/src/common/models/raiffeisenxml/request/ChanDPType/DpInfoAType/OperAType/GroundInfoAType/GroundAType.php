<?php

namespace common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType;

/**
 * Class representing GroundAType
 */
class GroundAType
{

    /**
     * Номер строки по порядку
     *
     * @property string $strNum
     */
    private $strNum = null;

    /**
     * Информация о документе-основании
     *
     * @property \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Gets as strNum
     *
     * Номер строки по порядку
     *
     * @return string
     */
    public function getStrNum()
    {
        return $this->strNum;
    }

    /**
     * Sets a new strNum
     *
     * Номер строки по порядку
     *
     * @param string $strNum
     * @return static
     */
    public function setStrNum($strNum)
    {
        $this->strNum = $strNum;
        return $this;
    }

    /**
     * Gets as docData
     *
     * Информация о документе-основании
     *
     * @return \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Информация о документе-основании
     *
     * @param \common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\ChanDPType\DpInfoAType\OperAType\GroundInfoAType\GroundAType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }


}

