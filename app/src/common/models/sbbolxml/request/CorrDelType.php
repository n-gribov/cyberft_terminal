<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorrDelType
 *
 *
 * XSD Type: CorrDel
 */
class CorrDelType
{

    /**
     * Идентификатор корреспондента в СББ
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор корреспондента в СББ
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор корреспондента в СББ
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }


}

