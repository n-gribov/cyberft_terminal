<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FinancialSituationInfoType
 *
 * Сведения о финансовом положении
 * XSD Type: FinancialSituationInfo
 */
class FinancialSituationInfoType
{

    /**
     * Вид сведений 1
     *
     * @property \common\models\sbbolxml\request\FinancialSituationInfoTypeOneType $financialSituationInfoTypeOne
     */
    private $financialSituationInfoTypeOne = null;

    /**
     * Вид сведений 2
     *
     * @property \common\models\sbbolxml\request\FinancialSituationInfoTypeTwoType $financialSituationInfoTypeTwo
     */
    private $financialSituationInfoTypeTwo = null;

    /**
     * Вид сведений 3
     *
     * @property \common\models\sbbolxml\request\FinancialSituationInfoTypeThreeType $financialSituationInfoTypeThree
     */
    private $financialSituationInfoTypeThree = null;

    /**
     * Gets as financialSituationInfoTypeOne
     *
     * Вид сведений 1
     *
     * @return \common\models\sbbolxml\request\FinancialSituationInfoTypeOneType
     */
    public function getFinancialSituationInfoTypeOne()
    {
        return $this->financialSituationInfoTypeOne;
    }

    /**
     * Sets a new financialSituationInfoTypeOne
     *
     * Вид сведений 1
     *
     * @param \common\models\sbbolxml\request\FinancialSituationInfoTypeOneType $financialSituationInfoTypeOne
     * @return static
     */
    public function setFinancialSituationInfoTypeOne(\common\models\sbbolxml\request\FinancialSituationInfoTypeOneType $financialSituationInfoTypeOne)
    {
        $this->financialSituationInfoTypeOne = $financialSituationInfoTypeOne;
        return $this;
    }

    /**
     * Gets as financialSituationInfoTypeTwo
     *
     * Вид сведений 2
     *
     * @return \common\models\sbbolxml\request\FinancialSituationInfoTypeTwoType
     */
    public function getFinancialSituationInfoTypeTwo()
    {
        return $this->financialSituationInfoTypeTwo;
    }

    /**
     * Sets a new financialSituationInfoTypeTwo
     *
     * Вид сведений 2
     *
     * @param \common\models\sbbolxml\request\FinancialSituationInfoTypeTwoType $financialSituationInfoTypeTwo
     * @return static
     */
    public function setFinancialSituationInfoTypeTwo(\common\models\sbbolxml\request\FinancialSituationInfoTypeTwoType $financialSituationInfoTypeTwo)
    {
        $this->financialSituationInfoTypeTwo = $financialSituationInfoTypeTwo;
        return $this;
    }

    /**
     * Gets as financialSituationInfoTypeThree
     *
     * Вид сведений 3
     *
     * @return \common\models\sbbolxml\request\FinancialSituationInfoTypeThreeType
     */
    public function getFinancialSituationInfoTypeThree()
    {
        return $this->financialSituationInfoTypeThree;
    }

    /**
     * Sets a new financialSituationInfoTypeThree
     *
     * Вид сведений 3
     *
     * @param \common\models\sbbolxml\request\FinancialSituationInfoTypeThreeType $financialSituationInfoTypeThree
     * @return static
     */
    public function setFinancialSituationInfoTypeThree(\common\models\sbbolxml\request\FinancialSituationInfoTypeThreeType $financialSituationInfoTypeThree)
    {
        $this->financialSituationInfoTypeThree = $financialSituationInfoTypeThree;
        return $this;
    }


}

