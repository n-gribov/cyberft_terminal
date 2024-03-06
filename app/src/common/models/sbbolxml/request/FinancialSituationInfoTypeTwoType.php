<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FinancialSituationInfoTypeTwoType
 *
 * Вид сведений 2
 * XSD Type: FinancialSituationInfoTypeTwo
 */
class FinancialSituationInfoTypeTwoType
{

    /**
     * Факты неисполнения юридическим лицом своих денежных обязательств по причине отсутствия денежных средств на
     *  банковских счетах по состоянию на дату предоставления документов в Банк
     *  (имеются – true, отсутствуют – false)
     *
     * @property boolean $defaultOnAnObligation
     */
    private $defaultOnAnObligation = null;

    /**
     * Gets as defaultOnAnObligation
     *
     * Факты неисполнения юридическим лицом своих денежных обязательств по причине отсутствия денежных средств на
     *  банковских счетах по состоянию на дату предоставления документов в Банк
     *  (имеются – true, отсутствуют – false)
     *
     * @return boolean
     */
    public function getDefaultOnAnObligation()
    {
        return $this->defaultOnAnObligation;
    }

    /**
     * Sets a new defaultOnAnObligation
     *
     * Факты неисполнения юридическим лицом своих денежных обязательств по причине отсутствия денежных средств на
     *  банковских счетах по состоянию на дату предоставления документов в Банк
     *  (имеются – true, отсутствуют – false)
     *
     * @param boolean $defaultOnAnObligation
     * @return static
     */
    public function setDefaultOnAnObligation($defaultOnAnObligation)
    {
        $this->defaultOnAnObligation = $defaultOnAnObligation;
        return $this;
    }


}

