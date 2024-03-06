<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AdmCashierGetLoginType
 *
 * Запрос логина «Вносителя средств»
 * XSD Type: AdmCashierGetLogin
 */
class AdmCashierGetLoginType
{

    /**
     * Идентификатор сущности «Вноситель средств»
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Gets as docId
     *
     * Идентификатор сущности «Вноситель средств»
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор сущности «Вноситель средств»
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }


}

