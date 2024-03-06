<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing GroundICSType
 *
 * Сведения о документе, на основании которого должны быть внесены изменения ПС
 * XSD Type: GroundICS
 */
class GroundICSType
{

    /**
     * Информация о документе-основании
     *
     * @property \common\models\sbbolxml\request\GroundDocDataICSType $docData
     */
    private $docData = null;

    /**
     * Gets as docData
     *
     * Информация о документе-основании
     *
     * @return \common\models\sbbolxml\request\GroundDocDataICSType
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
     * @param \common\models\sbbolxml\request\GroundDocDataICSType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\GroundDocDataICSType $docData)
    {
        $this->docData = $docData;
        return $this;
    }


}

