<?php

namespace common\models\raiffeisenxml\response\ResponseType;

/**
 * Class representing ClientAppUpdatesAType
 */
class ClientAppUpdatesAType
{

    /**
     * @property \common\models\raiffeisenxml\response\ClientAppUpdateType[] $clientAppUpdates
     */
    private $clientAppUpdates = [
        
    ];

    /**
     * Adds as clientAppUpdates
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ClientAppUpdateType $clientAppUpdates
     */
    public function addToClientAppUpdates(\common\models\raiffeisenxml\response\ClientAppUpdateType $clientAppUpdates)
    {
        $this->clientAppUpdates[] = $clientAppUpdates;
        return $this;
    }

    /**
     * isset clientAppUpdates
     *
     * @param int|string $index
     * @return bool
     */
    public function issetClientAppUpdates($index)
    {
        return isset($this->clientAppUpdates[$index]);
    }

    /**
     * unset clientAppUpdates
     *
     * @param int|string $index
     * @return void
     */
    public function unsetClientAppUpdates($index)
    {
        unset($this->clientAppUpdates[$index]);
    }

    /**
     * Gets as clientAppUpdates
     *
     * @return \common\models\raiffeisenxml\response\ClientAppUpdateType[]
     */
    public function getClientAppUpdates()
    {
        return $this->clientAppUpdates;
    }

    /**
     * Sets a new clientAppUpdates
     *
     * @param \common\models\raiffeisenxml\response\ClientAppUpdateType[] $clientAppUpdates
     * @return static
     */
    public function setClientAppUpdates(array $clientAppUpdates)
    {
        $this->clientAppUpdates = $clientAppUpdates;
        return $this;
    }


}

