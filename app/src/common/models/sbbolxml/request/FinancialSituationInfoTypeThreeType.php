<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FinancialSituationInfoTypeThreeType
 *
 * Вид сведений 3
 * XSD Type: FinancialSituationInfoTypeThree
 */
class FinancialSituationInfoTypeThreeType
{

    /**
     * Агентство и показатель рейтинга
     *
     * @property string $agencyAndRatingIndex
     */
    private $agencyAndRatingIndex = null;

    /**
     * Gets as agencyAndRatingIndex
     *
     * Агентство и показатель рейтинга
     *
     * @return string
     */
    public function getAgencyAndRatingIndex()
    {
        return $this->agencyAndRatingIndex;
    }

    /**
     * Sets a new agencyAndRatingIndex
     *
     * Агентство и показатель рейтинга
     *
     * @param string $agencyAndRatingIndex
     * @return static
     */
    public function setAgencyAndRatingIndex($agencyAndRatingIndex)
    {
        $this->agencyAndRatingIndex = $agencyAndRatingIndex;
        return $this;
    }


}

