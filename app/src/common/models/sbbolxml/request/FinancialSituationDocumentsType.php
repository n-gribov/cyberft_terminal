<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FinancialSituationDocumentsType
 *
 * Документы о финансовом положении
 * XSD Type: FinancialSituationDocuments
 */
class FinancialSituationDocumentsType
{

    /**
     * Копия бухгалтерского баланса и копия отчета о финансовом результате
     *
     * @property boolean $balanceSheet
     */
    private $balanceSheet = null;

    /**
     * Копия годовой бухгалтерской отчетности (бухгалтерский баланс, отчет о финансовом результате)
     *
     * @property boolean $financialStatements
     */
    private $financialStatements = null;

    /**
     * Копия годовой (либо квартальной) налоговой декларации с отметками налогового органа об их принятии или без такой
     *  отметки с приложением либо копии квитанции об отправке заказного письма с описью вложения (при направлении по почте), либо
     *  копии подтверждения отправки на бумажных носителях (при передаче в электронном виде)
     *
     * @property boolean $taxReturn
     */
    private $taxReturn = null;

    /**
     * Аудиторское заключение (копия аудиторского заключения на годовой отчет за прошедший год, в котором
     *  подтверждаются достоверность финансовой (бухгалтерской) отчетности и соответствие порядка ведения бухгалтерского учета
     *  законодательству Российской Федерации/международным стандартам финансовой отчетности (МСФО)
     *
     * @property boolean $auditReport
     */
    private $auditReport = null;

    /**
     * Справка об исполнении налогоплательщиком (плательщиком сборов, налоговым агентом) обязанности по уплате налогов,
     *  сборов, пеней, штрафов, выданная налоговым органом
     *
     * @property boolean $certificateOfTaxPayment
     */
    private $certificateOfTaxPayment = null;

    /**
     * Gets as balanceSheet
     *
     * Копия бухгалтерского баланса и копия отчета о финансовом результате
     *
     * @return boolean
     */
    public function getBalanceSheet()
    {
        return $this->balanceSheet;
    }

    /**
     * Sets a new balanceSheet
     *
     * Копия бухгалтерского баланса и копия отчета о финансовом результате
     *
     * @param boolean $balanceSheet
     * @return static
     */
    public function setBalanceSheet($balanceSheet)
    {
        $this->balanceSheet = $balanceSheet;
        return $this;
    }

    /**
     * Gets as financialStatements
     *
     * Копия годовой бухгалтерской отчетности (бухгалтерский баланс, отчет о финансовом результате)
     *
     * @return boolean
     */
    public function getFinancialStatements()
    {
        return $this->financialStatements;
    }

    /**
     * Sets a new financialStatements
     *
     * Копия годовой бухгалтерской отчетности (бухгалтерский баланс, отчет о финансовом результате)
     *
     * @param boolean $financialStatements
     * @return static
     */
    public function setFinancialStatements($financialStatements)
    {
        $this->financialStatements = $financialStatements;
        return $this;
    }

    /**
     * Gets as taxReturn
     *
     * Копия годовой (либо квартальной) налоговой декларации с отметками налогового органа об их принятии или без такой
     *  отметки с приложением либо копии квитанции об отправке заказного письма с описью вложения (при направлении по почте), либо
     *  копии подтверждения отправки на бумажных носителях (при передаче в электронном виде)
     *
     * @return boolean
     */
    public function getTaxReturn()
    {
        return $this->taxReturn;
    }

    /**
     * Sets a new taxReturn
     *
     * Копия годовой (либо квартальной) налоговой декларации с отметками налогового органа об их принятии или без такой
     *  отметки с приложением либо копии квитанции об отправке заказного письма с описью вложения (при направлении по почте), либо
     *  копии подтверждения отправки на бумажных носителях (при передаче в электронном виде)
     *
     * @param boolean $taxReturn
     * @return static
     */
    public function setTaxReturn($taxReturn)
    {
        $this->taxReturn = $taxReturn;
        return $this;
    }

    /**
     * Gets as auditReport
     *
     * Аудиторское заключение (копия аудиторского заключения на годовой отчет за прошедший год, в котором
     *  подтверждаются достоверность финансовой (бухгалтерской) отчетности и соответствие порядка ведения бухгалтерского учета
     *  законодательству Российской Федерации/международным стандартам финансовой отчетности (МСФО)
     *
     * @return boolean
     */
    public function getAuditReport()
    {
        return $this->auditReport;
    }

    /**
     * Sets a new auditReport
     *
     * Аудиторское заключение (копия аудиторского заключения на годовой отчет за прошедший год, в котором
     *  подтверждаются достоверность финансовой (бухгалтерской) отчетности и соответствие порядка ведения бухгалтерского учета
     *  законодательству Российской Федерации/международным стандартам финансовой отчетности (МСФО)
     *
     * @param boolean $auditReport
     * @return static
     */
    public function setAuditReport($auditReport)
    {
        $this->auditReport = $auditReport;
        return $this;
    }

    /**
     * Gets as certificateOfTaxPayment
     *
     * Справка об исполнении налогоплательщиком (плательщиком сборов, налоговым агентом) обязанности по уплате налогов,
     *  сборов, пеней, штрафов, выданная налоговым органом
     *
     * @return boolean
     */
    public function getCertificateOfTaxPayment()
    {
        return $this->certificateOfTaxPayment;
    }

    /**
     * Sets a new certificateOfTaxPayment
     *
     * Справка об исполнении налогоплательщиком (плательщиком сборов, налоговым агентом) обязанности по уплате налогов,
     *  сборов, пеней, штрафов, выданная налоговым органом
     *
     * @param boolean $certificateOfTaxPayment
     * @return static
     */
    public function setCertificateOfTaxPayment($certificateOfTaxPayment)
    {
        $this->certificateOfTaxPayment = $certificateOfTaxPayment;
        return $this;
    }


}

