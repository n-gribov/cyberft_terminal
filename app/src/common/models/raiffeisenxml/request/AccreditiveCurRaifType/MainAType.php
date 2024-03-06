<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType;

/**
 * Class representing MainAType
 */
class MainAType
{

    /**
     * Вид аккредитива.
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\AccreditiveTypeAType $accreditiveType
     */
    private $accreditiveType = null;

    /**
     * Исполняющий банк
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\ExecutiveBankAType $executiveBank
     */
    private $executiveBank = null;

    /**
     * Gets as accreditiveType
     *
     * Вид аккредитива.
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\AccreditiveTypeAType
     */
    public function getAccreditiveType()
    {
        return $this->accreditiveType;
    }

    /**
     * Sets a new accreditiveType
     *
     * Вид аккредитива.
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\AccreditiveTypeAType $accreditiveType
     * @return static
     */
    public function setAccreditiveType(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\AccreditiveTypeAType $accreditiveType)
    {
        $this->accreditiveType = $accreditiveType;
        return $this;
    }

    /**
     * Gets as executiveBank
     *
     * Исполняющий банк
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\ExecutiveBankAType
     */
    public function getExecutiveBank()
    {
        return $this->executiveBank;
    }

    /**
     * Sets a new executiveBank
     *
     * Исполняющий банк
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\ExecutiveBankAType $executiveBank
     * @return static
     */
    public function setExecutiveBank(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\ExecutiveBankAType $executiveBank)
    {
        $this->executiveBank = $executiveBank;
        return $this;
    }


}

