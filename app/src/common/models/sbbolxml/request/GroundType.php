<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing GroundType
 *
 * Сведения о документе, на основании которого должны быть внесены изменения ПС
 * XSD Type: Ground
 */
class GroundType
{

    /**
     * Номер строки по порядку
     *
     * @property integer $strNum
     */
    private $strNum = null;

    /**
     * Информация о документе-основании
     *
     * @property \common\models\sbbolxml\request\GroundDocDataType $docData
     */
    private $docData = null;

    /**
     * Gets as strNum
     *
     * Номер строки по порядку
     *
     * @return integer
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
     * @param integer $strNum
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
     * @return \common\models\sbbolxml\request\GroundDocDataType
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
     * @param \common\models\sbbolxml\request\GroundDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\GroundDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }


}

