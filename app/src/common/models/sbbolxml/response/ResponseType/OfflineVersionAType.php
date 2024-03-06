<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing OfflineVersionAType
 */
class OfflineVersionAType
{

    /**
     * @property string $currentVersion
     */
    private $currentVersion = null;

    /**
     * @property string $link
     */
    private $link = null;

    /**
     * @property boolean $critical
     */
    private $critical = null;

    /**
     * Gets as currentVersion
     *
     * @return string
     */
    public function getCurrentVersion()
    {
        return $this->currentVersion;
    }

    /**
     * Sets a new currentVersion
     *
     * @param string $currentVersion
     * @return static
     */
    public function setCurrentVersion($currentVersion)
    {
        $this->currentVersion = $currentVersion;
        return $this;
    }

    /**
     * Gets as link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets a new link
     *
     * @param string $link
     * @return static
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Gets as critical
     *
     * @return boolean
     */
    public function getCritical()
    {
        return $this->critical;
    }

    /**
     * Sets a new critical
     *
     * @param boolean $critical
     * @return static
     */
    public function setCritical($critical)
    {
        $this->critical = $critical;
        return $this;
    }


}

