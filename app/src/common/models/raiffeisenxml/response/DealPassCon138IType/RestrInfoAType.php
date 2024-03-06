<?php

namespace common\models\raiffeisenxml\response\DealPassCon138IType;

/**
 * Class representing RestrInfoAType
 */
class RestrInfoAType
{

    /**
     * @property \common\models\raiffeisenxml\response\DealPassCon138IType\RestrInfoAType\RestrAType[] $restr
     */
    private $restr = [
        
    ];

    /**
     * Adds as restr
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DealPassCon138IType\RestrInfoAType\RestrAType $restr
     */
    public function addToRestr(\common\models\raiffeisenxml\response\DealPassCon138IType\RestrInfoAType\RestrAType $restr)
    {
        $this->restr[] = $restr;
        return $this;
    }

    /**
     * isset restr
     *
     * @param int|string $index
     * @return bool
     */
    public function issetRestr($index)
    {
        return isset($this->restr[$index]);
    }

    /**
     * unset restr
     *
     * @param int|string $index
     * @return void
     */
    public function unsetRestr($index)
    {
        unset($this->restr[$index]);
    }

    /**
     * Gets as restr
     *
     * @return \common\models\raiffeisenxml\response\DealPassCon138IType\RestrInfoAType\RestrAType[]
     */
    public function getRestr()
    {
        return $this->restr;
    }

    /**
     * Sets a new restr
     *
     * @param \common\models\raiffeisenxml\response\DealPassCon138IType\RestrInfoAType\RestrAType[] $restr
     * @return static
     */
    public function setRestr(array $restr)
    {
        $this->restr = $restr;
        return $this;
    }


}

