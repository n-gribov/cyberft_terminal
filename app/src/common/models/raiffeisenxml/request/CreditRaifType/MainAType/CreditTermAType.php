<?php

namespace common\models\raiffeisenxml\request\CreditRaifType\MainAType;

/**
 * Class representing CreditTermAType
 */
class CreditTermAType
{

    /**
     * Срок
     *
     * @property float $term
     */
    private $term = null;

    /**
     * Тип срока. Возможные значения "Дней", "Месяцев".
     *
     * @property string $termType
     */
    private $termType = null;

    /**
     * Gets as term
     *
     * Срок
     *
     * @return float
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
     * @param float $term
     * @return static
     */
    public function setTerm($term)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Gets as termType
     *
     * Тип срока. Возможные значения "Дней", "Месяцев".
     *
     * @return string
     */
    public function getTermType()
    {
        return $this->termType;
    }

    /**
     * Sets a new termType
     *
     * Тип срока. Возможные значения "Дней", "Месяцев".
     *
     * @param string $termType
     * @return static
     */
    public function setTermType($termType)
    {
        $this->termType = $termType;
        return $this;
    }


}

