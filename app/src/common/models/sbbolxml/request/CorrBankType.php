<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorrBankType
 *
 *
 * XSD Type: CorrBank
 */
class CorrBankType
{

    /**
     * Наименование банка контрагента
     *
     * @property string $name
     */
    private $name = null;

    /**
     * БИК банка контрагента
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Номер корр. счёта банка контрагента
     *
     * @property string $correspAcc
     */
    private $correspAcc = null;

    /**
     * Gets as name
     *
     * Наименование банка контрагента
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
     * Наименование банка контрагента
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as bic
     *
     * БИК банка контрагента
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
     * БИК банка контрагента
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
     * Номер корр. счёта банка контрагента
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
     * Номер корр. счёта банка контрагента
     *
     * @param string $correspAcc
     * @return static
     */
    public function setCorrespAcc($correspAcc)
    {
        $this->correspAcc = $correspAcc;
        return $this;
    }


}

