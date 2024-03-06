<?php

namespace common\models\raiffeisenxml\request\RequestType\LetterTicketAType;

/**
 * Class representing ReadLetterAType
 */
class ReadLetterAType
{

    /**
     * ФИО сотрудника, прочитавшего письмо.
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Дата и время прочтения письма.
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Внешний идентификатор организации, которая прочла письмо.
     *
     * @property string $orgID
     */
    private $orgID = null;

    /**
     * Gets as name
     *
     * ФИО сотрудника, прочитавшего письмо.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * ФИО сотрудника, прочитавшего письмо.
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата и время прочтения письма.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата и время прочтения письма.
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as orgID
     *
     * Внешний идентификатор организации, которая прочла письмо.
     *
     * @return string
     */
    public function getOrgID()
    {
        return $this->orgID;
    }

    /**
     * Sets a new orgID
     *
     * Внешний идентификатор организации, которая прочла письмо.
     *
     * @param string $orgID
     * @return static
     */
    public function setOrgID($orgID)
    {
        $this->orgID = $orgID;
        return $this;
    }


}

