<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing ClientAppUpdatesAType
 */
class ClientAppUpdatesAType
{

    /**
     * @property \common\models\sbbolxml\response\ClientAppUpdateType[] $clientAppUpdates
     */
    private $clientAppUpdates = array(
        
    );

    /**
     * Adds as clientAppUpdates
     *
     * @return static
     * @param \common\models\sbbolxml\response\ClientAppUpdateType $clientAppUpdates
     */
    public function addToClientAppUpdates(\common\models\sbbolxml\response\ClientAppUpdateType $clientAppUpdates)
    {
        $this->clientAppUpdates[] = $clientAppUpdates;
        return $this;
    }

    /**
     * isset clientAppUpdates
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetClientAppUpdates($index)
    {
        return isset($this->clientAppUpdates[$index]);
    }

    /**
     * unset clientAppUpdates
     *
     * @param scalar $index
     * @return void
     */
    public function unsetClientAppUpdates($index)
    {
        unset($this->clientAppUpdates[$index]);
    }

    /**
     * Gets as clientAppUpdates
     *
     * @return \common\models\sbbolxml\response\ClientAppUpdateType[]
     */
    public function getClientAppUpdates()
    {
        return $this->clientAppUpdates;
    }

    /**
     * Sets a new clientAppUpdates
     *
     * @param \common\models\sbbolxml\response\ClientAppUpdateType[] $clientAppUpdates
     * @return static
     */
    public function setClientAppUpdates(array $clientAppUpdates)
    {
        $this->clientAppUpdates = $clientAppUpdates;
        return $this;
    }


}

