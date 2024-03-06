<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ComDocDataType
 *
 *
 * XSD Type: ComDocData
 */
class ComDocDataType extends ComDocDataBaseType
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

