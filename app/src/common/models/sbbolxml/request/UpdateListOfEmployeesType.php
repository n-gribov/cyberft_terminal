<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing UpdateListOfEmployeesType
 *
 *
 * XSD Type: UpdateListOfEmployees
 */
class UpdateListOfEmployeesType
{

    /**
     * @property \common\models\sbbolxml\request\ClientStateType $clientState
     */
    private $clientState = null;

    /**
     * @property \DateTime $lastUpdate
     */
    private $lastUpdate = null;

    /**
     * Gets as clientState
     *
     * @return \common\models\sbbolxml\request\ClientStateType
     */
    public function getClientState()
    {
        return $this->clientState;
    }

    /**
     * Sets a new clientState
     *
     * @param \common\models\sbbolxml\request\ClientStateType $clientState
     * @return static
     */
    public function setClientState(\common\models\sbbolxml\request\ClientStateType $clientState)
    {
        $this->clientState = $clientState;
        return $this;
    }

    /**
     * Gets as lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Sets a new lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return static
     */
    public function setLastUpdate(\DateTime $lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }


}

