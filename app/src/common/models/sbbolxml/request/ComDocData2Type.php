<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ComDocData2Type
 *
 *
 * XSD Type: ComDocData2
 */
class ComDocData2Type extends ComDocDataBase2Type
{

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Gets as docNum
     *
     * Номер документа
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер документа
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }


}

