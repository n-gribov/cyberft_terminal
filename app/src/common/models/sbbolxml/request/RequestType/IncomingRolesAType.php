<?php

namespace common\models\sbbolxml\request\RequestType;

/**
 * Class representing IncomingRolesAType
 */
class IncomingRolesAType
{

    /**
     * @property string[] $role
     */
    private $role = array(
        
    );

    /**
     * Adds as role
     *
     * @return static
     * @param string $role
     */
    public function addToRole($role)
    {
        $this->role[] = $role;
        return $this;
    }

    /**
     * isset role
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetRole($index)
    {
        return isset($this->role[$index]);
    }

    /**
     * unset role
     *
     * @param scalar $index
     * @return void
     */
    public function unsetRole($index)
    {
        unset($this->role[$index]);
    }

    /**
     * Gets as role
     *
     * @return string[]
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Sets a new role
     *
     * @param string[] $role
     * @return static
     */
    public function setRole(array $role)
    {
        $this->role = $role;
        return $this;
    }


}

