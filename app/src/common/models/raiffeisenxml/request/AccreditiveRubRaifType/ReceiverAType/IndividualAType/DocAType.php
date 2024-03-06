<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\IndividualAType;

/**
 * Class representing DocAType
 */
class DocAType
{

    /**
     * Тип удостоверяющего документа
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Серия и номер документа
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Кем выдан документ
     *
     * @property string $issuedBy
     */
    private $issuedBy = null;

    /**
     * Когда выдан документ
     *
     * @property \DateTime $issuedDate
     */
    private $issuedDate = null;

    /**
     * Gets as type
     *
     * Тип удостоверяющего документа
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип удостоверяющего документа
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as num
     *
     * Серия и номер документа
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Серия и номер документа
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as issuedBy
     *
     * Кем выдан документ
     *
     * @return string
     */
    public function getIssuedBy()
    {
        return $this->issuedBy;
    }

    /**
     * Sets a new issuedBy
     *
     * Кем выдан документ
     *
     * @param string $issuedBy
     * @return static
     */
    public function setIssuedBy($issuedBy)
    {
        $this->issuedBy = $issuedBy;
        return $this;
    }

    /**
     * Gets as issuedDate
     *
     * Когда выдан документ
     *
     * @return \DateTime
     */
    public function getIssuedDate()
    {
        return $this->issuedDate;
    }

    /**
     * Sets a new issuedDate
     *
     * Когда выдан документ
     *
     * @param \DateTime $issuedDate
     * @return static
     */
    public function setIssuedDate(\DateTime $issuedDate)
    {
        $this->issuedDate = $issuedDate;
        return $this;
    }


}

