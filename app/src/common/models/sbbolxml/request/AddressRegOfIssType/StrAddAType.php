<?php

namespace common\models\sbbolxml\request\AddressRegOfIssType;

/**
 * Class representing StrAddAType
 */
class StrAddAType
{

    /**
     * Наименование (как в 1С)
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Сокращение (как в 1С)
     *
     * @property string $shortName
     */
    private $shortName = null;

    /**
     * Gets as name
     *
     * Наименование (как в 1С)
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Наименование (как в 1С)
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as shortName
     *
     * Сокращение (как в 1С)
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Sets a new shortName
     *
     * Сокращение (как в 1С)
     *
     * @param string $shortName
     * @return static
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }


}

