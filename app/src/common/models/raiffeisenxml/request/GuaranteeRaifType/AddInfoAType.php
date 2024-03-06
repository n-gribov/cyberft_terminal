<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType;

/**
 * Class representing AddInfoAType
 */
class AddInfoAType
{

    /**
     * № соглашения о выпуске гарантии
     *
     * @property string $agreementNum
     */
    private $agreementNum = null;

    /**
     * Дата соглашения
     *
     * @property \DateTime $agreementDate
     */
    private $agreementDate = null;

    /**
     * Паспорт сделки
     *
     * @property string $dealPass
     */
    private $dealPass = null;

    /**
     * Гарантия подчиняется
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType\SubmissionAType $submission
     */
    private $submission = null;

    /**
     * Документы, которые должны быть предоставлены по условиям гарантии
     *
     * @property string $reqDocs
     */
    private $reqDocs = null;

    /**
     * Счет списания денежного покрытия
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType\AccWriteOffCoverAType $accWriteOffCover
     */
    private $accWriteOffCover = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as agreementNum
     *
     * № соглашения о выпуске гарантии
     *
     * @return string
     */
    public function getAgreementNum()
    {
        return $this->agreementNum;
    }

    /**
     * Sets a new agreementNum
     *
     * № соглашения о выпуске гарантии
     *
     * @param string $agreementNum
     * @return static
     */
    public function setAgreementNum($agreementNum)
    {
        $this->agreementNum = $agreementNum;
        return $this;
    }

    /**
     * Gets as agreementDate
     *
     * Дата соглашения
     *
     * @return \DateTime
     */
    public function getAgreementDate()
    {
        return $this->agreementDate;
    }

    /**
     * Sets a new agreementDate
     *
     * Дата соглашения
     *
     * @param \DateTime $agreementDate
     * @return static
     */
    public function setAgreementDate(\DateTime $agreementDate)
    {
        $this->agreementDate = $agreementDate;
        return $this;
    }

    /**
     * Gets as dealPass
     *
     * Паспорт сделки
     *
     * @return string
     */
    public function getDealPass()
    {
        return $this->dealPass;
    }

    /**
     * Sets a new dealPass
     *
     * Паспорт сделки
     *
     * @param string $dealPass
     * @return static
     */
    public function setDealPass($dealPass)
    {
        $this->dealPass = $dealPass;
        return $this;
    }

    /**
     * Gets as submission
     *
     * Гарантия подчиняется
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType\SubmissionAType
     */
    public function getSubmission()
    {
        return $this->submission;
    }

    /**
     * Sets a new submission
     *
     * Гарантия подчиняется
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType\SubmissionAType $submission
     * @return static
     */
    public function setSubmission(\common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType\SubmissionAType $submission)
    {
        $this->submission = $submission;
        return $this;
    }

    /**
     * Gets as reqDocs
     *
     * Документы, которые должны быть предоставлены по условиям гарантии
     *
     * @return string
     */
    public function getReqDocs()
    {
        return $this->reqDocs;
    }

    /**
     * Sets a new reqDocs
     *
     * Документы, которые должны быть предоставлены по условиям гарантии
     *
     * @param string $reqDocs
     * @return static
     */
    public function setReqDocs($reqDocs)
    {
        $this->reqDocs = $reqDocs;
        return $this;
    }

    /**
     * Gets as accWriteOffCover
     *
     * Счет списания денежного покрытия
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType\AccWriteOffCoverAType
     */
    public function getAccWriteOffCover()
    {
        return $this->accWriteOffCover;
    }

    /**
     * Sets a new accWriteOffCover
     *
     * Счет списания денежного покрытия
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType\AccWriteOffCoverAType $accWriteOffCover
     * @return static
     */
    public function setAccWriteOffCover(\common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType\AccWriteOffCoverAType $accWriteOffCover)
    {
        $this->accWriteOffCover = $accWriteOffCover;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Дополнительная информация
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Дополнительная информация
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}

