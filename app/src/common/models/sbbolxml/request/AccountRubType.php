<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AccountRubType
 *
 * Реквизиты счёта перечисления средств на покупку
 * XSD Type: AccountRub
 */
class AccountRubType
{

    /**
     * Спиcание со счета
     *
     * @property \common\models\sbbolxml\request\AccountRubType\AccountAType $account
     */
    private $account = null;

    /**
     * Перечисление по платежному поручению
     *
     * @property \common\models\sbbolxml\request\AccountRubType\DocAType $doc
     */
    private $doc = null;

    /**
     * Gets as account
     *
     * Спиcание со счета
     *
     * @return \common\models\sbbolxml\request\AccountRubType\AccountAType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Спиcание со счета
     *
     * @param \common\models\sbbolxml\request\AccountRubType\AccountAType $account
     * @return static
     */
    public function setAccount(\common\models\sbbolxml\request\AccountRubType\AccountAType $account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Gets as doc
     *
     * Перечисление по платежному поручению
     *
     * @return \common\models\sbbolxml\request\AccountRubType\DocAType
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * Sets a new doc
     *
     * Перечисление по платежному поручению
     *
     * @param \common\models\sbbolxml\request\AccountRubType\DocAType $doc
     * @return static
     */
    public function setDoc(\common\models\sbbolxml\request\AccountRubType\DocAType $doc)
    {
        $this->doc = $doc;
        return $this;
    }


}

