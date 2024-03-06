<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FinancialSituationInfoAndDocsType
 *
 * Сведения (документы) о финансовом положении
 * XSD Type: FinancialSituationInfoAndDocs
 */
class FinancialSituationInfoAndDocsType
{

    /**
     * @property \common\models\sbbolxml\request\FinancialSituationInfoType $financialSituationInfo
     */
    private $financialSituationInfo = null;

    /**
     * @property \common\models\sbbolxml\request\FinancialSituationDocumentsType $financialSituationDocuments
     */
    private $financialSituationDocuments = null;

    /**
     * Gets as financialSituationInfo
     *
     * @return \common\models\sbbolxml\request\FinancialSituationInfoType
     */
    public function getFinancialSituationInfo()
    {
        return $this->financialSituationInfo;
    }

    /**
     * Sets a new financialSituationInfo
     *
     * @param \common\models\sbbolxml\request\FinancialSituationInfoType $financialSituationInfo
     * @return static
     */
    public function setFinancialSituationInfo(\common\models\sbbolxml\request\FinancialSituationInfoType $financialSituationInfo)
    {
        $this->financialSituationInfo = $financialSituationInfo;
        return $this;
    }

    /**
     * Gets as financialSituationDocuments
     *
     * @return \common\models\sbbolxml\request\FinancialSituationDocumentsType
     */
    public function getFinancialSituationDocuments()
    {
        return $this->financialSituationDocuments;
    }

    /**
     * Sets a new financialSituationDocuments
     *
     * @param \common\models\sbbolxml\request\FinancialSituationDocumentsType $financialSituationDocuments
     * @return static
     */
    public function setFinancialSituationDocuments(\common\models\sbbolxml\request\FinancialSituationDocumentsType $financialSituationDocuments)
    {
        $this->financialSituationDocuments = $financialSituationDocuments;
        return $this;
    }


}

