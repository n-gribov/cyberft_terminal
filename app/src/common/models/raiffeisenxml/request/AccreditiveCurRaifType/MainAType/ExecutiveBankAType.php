<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType;

/**
 * Class representing ExecutiveBankAType
 */
class ExecutiveBankAType
{

    /**
     * Возможные значения: "ЗАО Райффайзен банк, Москва", "другое"
     *
     * @property string $executiveBank
     */
    private $executiveBank = null;

    /**
     * Другой банк
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\ExecutiveBankAType\OtherBankAType $otherBank
     */
    private $otherBank = null;

    /**
     * Gets as executiveBank
     *
     * Возможные значения: "ЗАО Райффайзен банк, Москва", "другое"
     *
     * @return string
     */
    public function getExecutiveBank()
    {
        return $this->executiveBank;
    }

    /**
     * Sets a new executiveBank
     *
     * Возможные значения: "ЗАО Райффайзен банк, Москва", "другое"
     *
     * @param string $executiveBank
     * @return static
     */
    public function setExecutiveBank($executiveBank)
    {
        $this->executiveBank = $executiveBank;
        return $this;
    }

    /**
     * Gets as otherBank
     *
     * Другой банк
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\ExecutiveBankAType\OtherBankAType
     */
    public function getOtherBank()
    {
        return $this->otherBank;
    }

    /**
     * Sets a new otherBank
     *
     * Другой банк
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\ExecutiveBankAType\OtherBankAType $otherBank
     * @return static
     */
    public function setOtherBank(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType\ExecutiveBankAType\OtherBankAType $otherBank)
    {
        $this->otherBank = $otherBank;
        return $this;
    }


}

