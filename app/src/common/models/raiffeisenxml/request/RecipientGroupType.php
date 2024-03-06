<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing RecipientGroupType
 *
 *
 * XSD Type: RecipientGroup
 */
class RecipientGroupType
{

    /**
     * @property string $groupName
     */
    private $groupName = null;

    /**
     * @property string $userId
     */
    private $userId = null;

    /**
     * Gets as groupName
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Sets a new groupName
     *
     * @param string $groupName
     * @return static
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * Gets as userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets a new userId
     *
     * @param string $userId
     * @return static
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }


}

