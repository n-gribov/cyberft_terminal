<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType;

/**
 * Class representing DocumentsAType
 */
class DocumentsAType
{

    /**
     * Перечень документов и требования к предоставляемым документам
     *
     * @property string $listAndReq
     */
    private $listAndReq = null;

    /**
     * Срок предоставления
     *
     * @property string $submission
     */
    private $submission = null;

    /**
     * Gets as listAndReq
     *
     * Перечень документов и требования к предоставляемым документам
     *
     * @return string
     */
    public function getListAndReq()
    {
        return $this->listAndReq;
    }

    /**
     * Sets a new listAndReq
     *
     * Перечень документов и требования к предоставляемым документам
     *
     * @param string $listAndReq
     * @return static
     */
    public function setListAndReq($listAndReq)
    {
        $this->listAndReq = $listAndReq;
        return $this;
    }

    /**
     * Gets as submission
     *
     * Срок предоставления
     *
     * @return string
     */
    public function getSubmission()
    {
        return $this->submission;
    }

    /**
     * Sets a new submission
     *
     * Срок предоставления
     *
     * @param string $submission
     * @return static
     */
    public function setSubmission($submission)
    {
        $this->submission = $submission;
        return $this;
    }


}

