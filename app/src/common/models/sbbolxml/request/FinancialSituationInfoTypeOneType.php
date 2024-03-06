<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FinancialSituationInfoTypeOneType
 *
 * Вид сведений 1
 * XSD Type: FinancialSituationInfoTypeOne
 */
class FinancialSituationInfoTypeOneType
{

    /**
     * Производство по делу о несостоятельности (банкротстве) в отношении юридического лица по состоянию на дату
     *  предоставления документов в Банк
     *  (ведется – true, не ведется – false)
     *
     * @property boolean $matterOfBankruptcy
     */
    private $matterOfBankruptcy = null;

    /**
     * Процедура ликвидации в отношении юридического лица по состоянию на дату представления документов в Банк
     *  (проводится – true, не проводится – false)
     *
     * @property boolean $liquidationProcedure
     */
    private $liquidationProcedure = null;

    /**
     * Вступившие в силу решения судебных органов в отношении юридического лица о признании его несостоятельным
     *  (банкротом) по состоянию на дату предоставления документов в Банк
     *  (имеются – true, отсутствуют – false)
     *
     * @property boolean $bankruptcyCourtDecision
     */
    private $bankruptcyCourtDecision = null;

    /**
     * Gets as matterOfBankruptcy
     *
     * Производство по делу о несостоятельности (банкротстве) в отношении юридического лица по состоянию на дату
     *  предоставления документов в Банк
     *  (ведется – true, не ведется – false)
     *
     * @return boolean
     */
    public function getMatterOfBankruptcy()
    {
        return $this->matterOfBankruptcy;
    }

    /**
     * Sets a new matterOfBankruptcy
     *
     * Производство по делу о несостоятельности (банкротстве) в отношении юридического лица по состоянию на дату
     *  предоставления документов в Банк
     *  (ведется – true, не ведется – false)
     *
     * @param boolean $matterOfBankruptcy
     * @return static
     */
    public function setMatterOfBankruptcy($matterOfBankruptcy)
    {
        $this->matterOfBankruptcy = $matterOfBankruptcy;
        return $this;
    }

    /**
     * Gets as liquidationProcedure
     *
     * Процедура ликвидации в отношении юридического лица по состоянию на дату представления документов в Банк
     *  (проводится – true, не проводится – false)
     *
     * @return boolean
     */
    public function getLiquidationProcedure()
    {
        return $this->liquidationProcedure;
    }

    /**
     * Sets a new liquidationProcedure
     *
     * Процедура ликвидации в отношении юридического лица по состоянию на дату представления документов в Банк
     *  (проводится – true, не проводится – false)
     *
     * @param boolean $liquidationProcedure
     * @return static
     */
    public function setLiquidationProcedure($liquidationProcedure)
    {
        $this->liquidationProcedure = $liquidationProcedure;
        return $this;
    }

    /**
     * Gets as bankruptcyCourtDecision
     *
     * Вступившие в силу решения судебных органов в отношении юридического лица о признании его несостоятельным
     *  (банкротом) по состоянию на дату предоставления документов в Банк
     *  (имеются – true, отсутствуют – false)
     *
     * @return boolean
     */
    public function getBankruptcyCourtDecision()
    {
        return $this->bankruptcyCourtDecision;
    }

    /**
     * Sets a new bankruptcyCourtDecision
     *
     * Вступившие в силу решения судебных органов в отношении юридического лица о признании его несостоятельным
     *  (банкротом) по состоянию на дату предоставления документов в Банк
     *  (имеются – true, отсутствуют – false)
     *
     * @param boolean $bankruptcyCourtDecision
     * @return static
     */
    public function setBankruptcyCourtDecision($bankruptcyCourtDecision)
    {
        $this->bankruptcyCourtDecision = $bankruptcyCourtDecision;
        return $this;
    }


}

