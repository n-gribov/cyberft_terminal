<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ISKRequestType
 *
 * Запрос сведений об организации
 * XSD Type: ISKRequest
 */
class ISKRequestType
{

    /**
     * Необходимо предоставить сведения об организации
     *
     * @property boolean $iSKRequired
     */
    private $iSKRequired = null;

    /**
     * Идентификатор информационных сведений юридического лица или индивидуального предпринимателя в СББ
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Gets as iSKRequired
     *
     * Необходимо предоставить сведения об организации
     *
     * @return boolean
     */
    public function getISKRequired()
    {
        return $this->iSKRequired;
    }

    /**
     * Sets a new iSKRequired
     *
     * Необходимо предоставить сведения об организации
     *
     * @param boolean $iSKRequired
     * @return static
     */
    public function setISKRequired($iSKRequired)
    {
        $this->iSKRequired = $iSKRequired;
        return $this;
    }

    /**
     * Gets as docExtId
     *
     * Идентификатор информационных сведений юридического лица или индивидуального предпринимателя в СББ
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
     * Идентификатор информационных сведений юридического лица или индивидуального предпринимателя в СББ
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

