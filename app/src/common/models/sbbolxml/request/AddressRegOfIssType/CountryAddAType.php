<?php

namespace common\models\sbbolxml\request\AddressRegOfIssType;

/**
 * Class representing CountryAddAType
 */
class CountryAddAType
{

    /**
     * Полное наименование в соответствии с бщероссийским классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Краткое наименование в соответствии с Общероссийским
     *  классификатором стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @property string $shortName
     */
    private $shortName = null;

    /**
     * Цифровой Код в соответствии с Общероссийским классификатором стран
     *  мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Gets as name
     *
     * Полное наименование в соответствии с бщероссийским классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
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
     * Полное наименование в соответствии с бщероссийским классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
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
     * Краткое наименование в соответствии с Общероссийским
     *  классификатором стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
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
     * Краткое наименование в соответствии с Общероссийским
     *  классификатором стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @param string $shortName
     * @return static
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * Gets as code
     *
     * Цифровой Код в соответствии с Общероссийским классификатором стран
     *  мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Цифровой Код в соответствии с Общероссийским классификатором стран
     *  мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }


}

