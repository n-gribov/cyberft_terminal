<?php

namespace common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType\AccountAType;

/**
 * Class representing BankAType
 */
class BankAType
{

    /**
     * БИК
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * кор счет
     *
     * @property string $correspAcc
     */
    private $correspAcc = null;

    /**
     * Наименование Банка
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Gets as bic
     *
     * БИК
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * БИК
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as correspAcc
     *
     * кор счет
     *
     * @return string
     */
    public function getCorrespAcc()
    {
        return $this->correspAcc;
    }

    /**
     * Sets a new correspAcc
     *
     * кор счет
     *
     * @param string $correspAcc
     * @return static
     */
    public function setCorrespAcc($correspAcc)
    {
        $this->correspAcc = $correspAcc;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование Банка
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Наименование Банка
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}

