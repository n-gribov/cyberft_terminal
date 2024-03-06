<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DzoAccAttachDocDataType
 *
 * Реквизиты документа
 * XSD Type: DzoAccAttachDocData
 */
class DzoAccAttachDocDataType
{

    /**
     * Дата создания документа по местному времени
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер сформированного документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Gets as docDate
     *
     * Дата создания документа по местному времени
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата создания документа по местному времени
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер сформированного документа
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
     * Номер сформированного документа
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

