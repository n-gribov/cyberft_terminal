<?php

namespace common\models\raiffeisenxml\request\RequestType;

/**
 * Class representing DictsAType
 */
class DictsAType
{

    /**
     * Репликация справочников
     *
     * @property \common\models\raiffeisenxml\request\DictType[] $dict
     */
    private $dict = [
        
    ];

    /**
     * Adds as dict
     *
     * Репликация справочников
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\DictType $dict
     */
    public function addToDict(\common\models\raiffeisenxml\request\DictType $dict)
    {
        $this->dict[] = $dict;
        return $this;
    }

    /**
     * isset dict
     *
     * Репликация справочников
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDict($index)
    {
        return isset($this->dict[$index]);
    }

    /**
     * unset dict
     *
     * Репликация справочников
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDict($index)
    {
        unset($this->dict[$index]);
    }

    /**
     * Gets as dict
     *
     * Репликация справочников
     *
     * @return \common\models\raiffeisenxml\request\DictType[]
     */
    public function getDict()
    {
        return $this->dict;
    }

    /**
     * Sets a new dict
     *
     * Репликация справочников
     *
     * @param \common\models\raiffeisenxml\request\DictType[] $dict
     * @return static
     */
    public function setDict(array $dict)
    {
        $this->dict = $dict;
        return $this;
    }


}

