<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AccountReturnType
 *
 * Cчет возврата вклада и %%
 * XSD Type: AccountReturn
 */
class AccountReturnType
{

    /**
     * Номер счёта
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * Полное международное наименование организации
     *
     * @property string $beneficiar
     */
    private $beneficiar = null;

    /**
     * Международный БИК банка бенефициара
     *
     * @property string $benefBankSwift
     */
    private $benefBankSwift = null;

    /**
     * Наименование банка бенефициара
     *
     * @property string $benefBankName
     */
    private $benefBankName = null;

    /**
     * Международный БИК банка- корреспондента
     *
     * @property string $iMediaBankSwift
     */
    private $iMediaBankSwift = null;

    /**
     * Наименование банка- корреспондента
     *
     * @property string $iMediaBankName
     */
    private $iMediaBankName = null;

    /**
     * Реквизиты банка, в котором открыт счёт
     *
     * @property \common\models\sbbolxml\request\AccountReturnType\BankAType $bank
     */
    private $bank = null;

    /**
     * Gets as accNum
     *
     * Номер счёта
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Номер счёта
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as beneficiar
     *
     * Полное международное наименование организации
     *
     * @return string
     */
    public function getBeneficiar()
    {
        return $this->beneficiar;
    }

    /**
     * Sets a new beneficiar
     *
     * Полное международное наименование организации
     *
     * @param string $beneficiar
     * @return static
     */
    public function setBeneficiar($beneficiar)
    {
        $this->beneficiar = $beneficiar;
        return $this;
    }

    /**
     * Gets as benefBankSwift
     *
     * Международный БИК банка бенефициара
     *
     * @return string
     */
    public function getBenefBankSwift()
    {
        return $this->benefBankSwift;
    }

    /**
     * Sets a new benefBankSwift
     *
     * Международный БИК банка бенефициара
     *
     * @param string $benefBankSwift
     * @return static
     */
    public function setBenefBankSwift($benefBankSwift)
    {
        $this->benefBankSwift = $benefBankSwift;
        return $this;
    }

    /**
     * Gets as benefBankName
     *
     * Наименование банка бенефициара
     *
     * @return string
     */
    public function getBenefBankName()
    {
        return $this->benefBankName;
    }

    /**
     * Sets a new benefBankName
     *
     * Наименование банка бенефициара
     *
     * @param string $benefBankName
     * @return static
     */
    public function setBenefBankName($benefBankName)
    {
        $this->benefBankName = $benefBankName;
        return $this;
    }

    /**
     * Gets as iMediaBankSwift
     *
     * Международный БИК банка- корреспондента
     *
     * @return string
     */
    public function getIMediaBankSwift()
    {
        return $this->iMediaBankSwift;
    }

    /**
     * Sets a new iMediaBankSwift
     *
     * Международный БИК банка- корреспондента
     *
     * @param string $iMediaBankSwift
     * @return static
     */
    public function setIMediaBankSwift($iMediaBankSwift)
    {
        $this->iMediaBankSwift = $iMediaBankSwift;
        return $this;
    }

    /**
     * Gets as iMediaBankName
     *
     * Наименование банка- корреспондента
     *
     * @return string
     */
    public function getIMediaBankName()
    {
        return $this->iMediaBankName;
    }

    /**
     * Sets a new iMediaBankName
     *
     * Наименование банка- корреспондента
     *
     * @param string $iMediaBankName
     * @return static
     */
    public function setIMediaBankName($iMediaBankName)
    {
        $this->iMediaBankName = $iMediaBankName;
        return $this;
    }

    /**
     * Gets as bank
     *
     * Реквизиты банка, в котором открыт счёт
     *
     * @return \common\models\sbbolxml\request\AccountReturnType\BankAType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Реквизиты банка, в котором открыт счёт
     *
     * @param \common\models\sbbolxml\request\AccountReturnType\BankAType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\request\AccountReturnType\BankAType $bank)
    {
        $this->bank = $bank;
        return $this;
    }


}

