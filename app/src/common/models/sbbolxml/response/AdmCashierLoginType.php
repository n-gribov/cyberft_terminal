<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AdmCashierLoginType
 *
 * Передача логина вносителя средств в УС
 * XSD Type: AdmCashierLogin
 */
class AdmCashierLoginType
{

    /**
     * Идентификатор сущности «Вноситель средств»
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Логин вносителя
     *
     * @property string $login
     */
    private $login = null;

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

    /**
     * Gets as login
     *
     * Логин вносителя
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Sets a new login
     *
     * Логин вносителя
     *
     * @param string $login
     * @return static
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }


}

