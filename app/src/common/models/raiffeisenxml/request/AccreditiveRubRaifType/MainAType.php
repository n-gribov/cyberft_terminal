<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType;

/**
 * Class representing MainAType
 */
class MainAType
{

    /**
     * Сумма документа
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Срок
     *
     * @property \DateTime $term
     */
    private $term = null;

    /**
     * Возможные значения: "отзывный", "безотзывный".
     *
     * @property string $revocable
     */
    private $revocable = null;

    /**
     * Возможные значения: "покрытый", "гарантированный".
     *
     * @property string $guaranteed
     */
    private $guaranteed = null;

    /**
     * Счет№ + реквизиты банка
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\MainAType\AccountAType $account
     */
    private $account = null;

    /**
     * Gets as sum
     *
     * Сумма документа
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма документа
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as term
     *
     * Срок
     *
     * @return \DateTime
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Sets a new term
     *
     * Срок
     *
     * @param \DateTime $term
     * @return static
     */
    public function setTerm(\DateTime $term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Gets as revocable
     *
     * Возможные значения: "отзывный", "безотзывный".
     *
     * @return string
     */
    public function getRevocable()
    {
        return $this->revocable;
    }

    /**
     * Sets a new revocable
     *
     * Возможные значения: "отзывный", "безотзывный".
     *
     * @param string $revocable
     * @return static
     */
    public function setRevocable($revocable)
    {
        $this->revocable = $revocable;
        return $this;
    }

    /**
     * Gets as guaranteed
     *
     * Возможные значения: "покрытый", "гарантированный".
     *
     * @return string
     */
    public function getGuaranteed()
    {
        return $this->guaranteed;
    }

    /**
     * Sets a new guaranteed
     *
     * Возможные значения: "покрытый", "гарантированный".
     *
     * @param string $guaranteed
     * @return static
     */
    public function setGuaranteed($guaranteed)
    {
        $this->guaranteed = $guaranteed;
        return $this;
    }

    /**
     * Gets as account
     *
     * Счет№ + реквизиты банка
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\MainAType\AccountAType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Счет№ + реквизиты банка
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\MainAType\AccountAType $account
     * @return static
     */
    public function setAccount(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\MainAType\AccountAType $account)
    {
        $this->account = $account;
        return $this;
    }


}

