<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType\ExecutiveBankAType;

/**
 * Class representing ExecutionAType
 */
class ExecutionAType
{

    /**
     * Возможные значения: "По предоставлении документов", "С отсрочкой исполнения", "Иным способом".
     *
     * @property string $way
     */
    private $way = null;

    /**
     * Иной способ исполнения
     *
     * @property string $anotherWay
     */
    private $anotherWay = null;

    /**
     * Gets as way
     *
     * Возможные значения: "По предоставлении документов", "С отсрочкой исполнения", "Иным способом".
     *
     * @return string
     */
    public function getWay()
    {
        return $this->way;
    }

    /**
     * Sets a new way
     *
     * Возможные значения: "По предоставлении документов", "С отсрочкой исполнения", "Иным способом".
     *
     * @param string $way
     * @return static
     */
    public function setWay($way)
    {
        $this->way = $way;
        return $this;
    }

    /**
     * Gets as anotherWay
     *
     * Иной способ исполнения
     *
     * @return string
     */
    public function getAnotherWay()
    {
        return $this->anotherWay;
    }

    /**
     * Sets a new anotherWay
     *
     * Иной способ исполнения
     *
     * @param string $anotherWay
     * @return static
     */
    public function setAnotherWay($anotherWay)
    {
        $this->anotherWay = $anotherWay;
        return $this;
    }


}

