<?php

namespace common\models\sbbolxml\request\SuppDocsType;

/**
 * Class representing SuppDocAType
 */
class SuppDocAType
{

    /**
     * Внешний идентификатор сущности
     *
     * @property string $essenceId
     */
    private $essenceId = null;

    /**
     * Тип сущности
     *
     * @property string $essenceType
     */
    private $essenceType = null;

    /**
     * Gets as essenceId
     *
     * Внешний идентификатор сущности
     *
     * @return string
     */
    public function getEssenceId()
    {
        return $this->essenceId;
    }

    /**
     * Sets a new essenceId
     *
     * Внешний идентификатор сущности
     *
     * @param string $essenceId
     * @return static
     */
    public function setEssenceId($essenceId)
    {
        $this->essenceId = $essenceId;
        return $this;
    }

    /**
     * Gets as essenceType
     *
     * Тип сущности
     *
     * @return string
     */
    public function getEssenceType()
    {
        return $this->essenceType;
    }

    /**
     * Sets a new essenceType
     *
     * Тип сущности
     *
     * @param string $essenceType
     * @return static
     */
    public function setEssenceType($essenceType)
    {
        $this->essenceType = $essenceType;
        return $this;
    }


}

