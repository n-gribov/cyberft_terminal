<?php

namespace common\models\raiffeisenxml\request\LetterOfDepositRaifType;

/**
 * Class representing PercentsRefundAType
 */
class PercentsRefundAType
{

    /**
     * на счет в нашем Банке
     *
     * @property \common\models\raiffeisenxml\request\AccountType $acc
     */
    private $acc = null;

    /**
     * 59:Бенефициар
     *
     * @property \common\models\raiffeisenxml\request\Beneficiar59RaifType $beneficiar
     */
    private $beneficiar = null;

    /**
     * 57:Банк Бенефициара
     *
     * @property \common\models\raiffeisenxml\request\BankBeneficiar57DepoType $beneficiarBank
     */
    private $beneficiarBank = null;

    /**
     * 56:Банк-посредник
     *
     * @property \common\models\raiffeisenxml\request\ImediaBank56Type $imediaBank
     */
    private $imediaBank = null;

    /**
     * Получатель
     *
     * @property \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\PayeeAType $payee
     */
    private $payee = null;

    /**
     * Банк получателя
     *
     * @property \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\PayeeBankAType $payeeBank
     */
    private $payeeBank = null;

    /**
     * Банк посредник.
     *
     * @property \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\MediaBankAType $mediaBank
     */
    private $mediaBank = null;

    /**
     * Gets as acc
     *
     * на счет в нашем Банке
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * на счет в нашем Банке
     *
     * @param \common\models\raiffeisenxml\request\AccountType $acc
     * @return static
     */
    public function setAcc(\common\models\raiffeisenxml\request\AccountType $acc)
    {
        $this->acc = $acc;
        return $this;
    }

    /**
     * Gets as beneficiar
     *
     * 59:Бенефициар
     *
     * @return \common\models\raiffeisenxml\request\Beneficiar59RaifType
     */
    public function getBeneficiar()
    {
        return $this->beneficiar;
    }

    /**
     * Sets a new beneficiar
     *
     * 59:Бенефициар
     *
     * @param \common\models\raiffeisenxml\request\Beneficiar59RaifType $beneficiar
     * @return static
     */
    public function setBeneficiar(\common\models\raiffeisenxml\request\Beneficiar59RaifType $beneficiar)
    {
        $this->beneficiar = $beneficiar;
        return $this;
    }

    /**
     * Gets as beneficiarBank
     *
     * 57:Банк Бенефициара
     *
     * @return \common\models\raiffeisenxml\request\BankBeneficiar57DepoType
     */
    public function getBeneficiarBank()
    {
        return $this->beneficiarBank;
    }

    /**
     * Sets a new beneficiarBank
     *
     * 57:Банк Бенефициара
     *
     * @param \common\models\raiffeisenxml\request\BankBeneficiar57DepoType $beneficiarBank
     * @return static
     */
    public function setBeneficiarBank(\common\models\raiffeisenxml\request\BankBeneficiar57DepoType $beneficiarBank)
    {
        $this->beneficiarBank = $beneficiarBank;
        return $this;
    }

    /**
     * Gets as imediaBank
     *
     * 56:Банк-посредник
     *
     * @return \common\models\raiffeisenxml\request\ImediaBank56Type
     */
    public function getImediaBank()
    {
        return $this->imediaBank;
    }

    /**
     * Sets a new imediaBank
     *
     * 56:Банк-посредник
     *
     * @param \common\models\raiffeisenxml\request\ImediaBank56Type $imediaBank
     * @return static
     */
    public function setImediaBank(\common\models\raiffeisenxml\request\ImediaBank56Type $imediaBank)
    {
        $this->imediaBank = $imediaBank;
        return $this;
    }

    /**
     * Gets as payee
     *
     * Получатель
     *
     * @return \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\PayeeAType
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * Sets a new payee
     *
     * Получатель
     *
     * @param \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\PayeeAType $payee
     * @return static
     */
    public function setPayee(\common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\PayeeAType $payee)
    {
        $this->payee = $payee;
        return $this;
    }

    /**
     * Gets as payeeBank
     *
     * Банк получателя
     *
     * @return \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\PayeeBankAType
     */
    public function getPayeeBank()
    {
        return $this->payeeBank;
    }

    /**
     * Sets a new payeeBank
     *
     * Банк получателя
     *
     * @param \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\PayeeBankAType $payeeBank
     * @return static
     */
    public function setPayeeBank(\common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\PayeeBankAType $payeeBank)
    {
        $this->payeeBank = $payeeBank;
        return $this;
    }

    /**
     * Gets as mediaBank
     *
     * Банк посредник.
     *
     * @return \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\MediaBankAType
     */
    public function getMediaBank()
    {
        return $this->mediaBank;
    }

    /**
     * Sets a new mediaBank
     *
     * Банк посредник.
     *
     * @param \common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\MediaBankAType $mediaBank
     * @return static
     */
    public function setMediaBank(\common\models\raiffeisenxml\request\LetterOfDepositRaifType\PercentsRefundAType\MediaBankAType $mediaBank)
    {
        $this->mediaBank = $mediaBank;
        return $this;
    }


}

