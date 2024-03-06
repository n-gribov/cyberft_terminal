<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType;

/**
 * Class representing DocumentsAType
 */
class DocumentsAType
{

    /**
     * Требуемые документы.
     *
     * @property string $requiredDocs
     */
    private $requiredDocs = null;

    /**
     * Период предоставления документов.
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocumentsAType\SubmissionPeriodAType $submissionPeriod
     */
    private $submissionPeriod = null;

    /**
     * Gets as requiredDocs
     *
     * Требуемые документы.
     *
     * @return string
     */
    public function getRequiredDocs()
    {
        return $this->requiredDocs;
    }

    /**
     * Sets a new requiredDocs
     *
     * Требуемые документы.
     *
     * @param string $requiredDocs
     * @return static
     */
    public function setRequiredDocs($requiredDocs)
    {
        $this->requiredDocs = $requiredDocs;
        return $this;
    }

    /**
     * Gets as submissionPeriod
     *
     * Период предоставления документов.
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocumentsAType\SubmissionPeriodAType
     */
    public function getSubmissionPeriod()
    {
        return $this->submissionPeriod;
    }

    /**
     * Sets a new submissionPeriod
     *
     * Период предоставления документов.
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocumentsAType\SubmissionPeriodAType $submissionPeriod
     * @return static
     */
    public function setSubmissionPeriod(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocumentsAType\SubmissionPeriodAType $submissionPeriod)
    {
        $this->submissionPeriod = $submissionPeriod;
        return $this;
    }


}

