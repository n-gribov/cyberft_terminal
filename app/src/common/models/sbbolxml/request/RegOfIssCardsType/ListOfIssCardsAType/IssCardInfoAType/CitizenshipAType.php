<?php

namespace common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType;

use common\models\sbbolxml\request\CitizenshipType;

/**
 * Class representing CitizenshipAType
 */
class CitizenshipAType extends CitizenshipType
{

    /**
     * Цифровой Код в соответствии с Общероссийским классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Трехбуквенный код в соответствии с Общероссийским
     *  классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @property string $letterCode
     */
    private $letterCode = null;

    /**
     * Gets as code
     *
     * Цифровой Код в соответствии с Общероссийским классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
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
     * Цифровой Код в соответствии с Общероссийским классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as letterCode
     *
     * Трехбуквенный код в соответствии с Общероссийским
     *  классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @return string
     */
    public function getLetterCode()
    {
        return $this->letterCode;
    }

    /**
     * Sets a new letterCode
     *
     * Трехбуквенный код в соответствии с Общероссийским
     *  классификатором
     *  стран мира OK (MK (ИСО 3166) 004-97) 025-2001 (ОКСМ)
     *
     * @param string $letterCode
     * @return static
     */
    public function setLetterCode($letterCode)
    {
        $this->letterCode = $letterCode;
        return $this;
    }


}

