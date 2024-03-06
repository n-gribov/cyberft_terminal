<?php

namespace common\models\sbbolxml\response;

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
     * Международный БИК банка бенефициара
     *
     * @property string $benefBankSwift
     */
    private $benefBankSwift = null;

    /**
     * Международный БИК банка- корреспондента
     *
     * @property string $iMediaBankSwift
     */
    private $iMediaBankSwift = null;

    /**
     * Реквизиты банка, в котором открыт счёт
     *
     * @property \common\models\sbbolxml\response\AccountReturnType\BankAType $bank
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
     * Gets as bank
     *
     * Реквизиты банка, в котором открыт счёт
     *
     * @return \common\models\sbbolxml\response\AccountReturnType\BankAType
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
     * @param \common\models\sbbolxml\response\AccountReturnType\BankAType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\response\AccountReturnType\BankAType $bank)
    {
        $this->bank = $bank;
        return $this;
    }


}

