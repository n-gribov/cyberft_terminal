<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CredBasePaymentsType
 *
 * Основания заполнения графика платежей по возврату основного долга и процентных платежей
 * XSD Type: CredBasePayments
 */
class CredBasePaymentsType
{

    /**
     * Сведения из кредитного договора (0 – признак не установлен; 1 – признак установлен)
     *
     * @property boolean $credData
     */
    private $credData = null;

    /**
     * Оценочные данные (0 – признак не установлен; 1 – признак установлен)
     *
     * @property boolean $evaluationData
     */
    private $evaluationData = null;

    /**
     * Gets as credData
     *
     * Сведения из кредитного договора (0 – признак не установлен; 1 – признак установлен)
     *
     * @return boolean
     */
    public function getCredData()
    {
        return $this->credData;
    }

    /**
     * Sets a new credData
     *
     * Сведения из кредитного договора (0 – признак не установлен; 1 – признак установлен)
     *
     * @param boolean $credData
     * @return static
     */
    public function setCredData($credData)
    {
        $this->credData = $credData;
        return $this;
    }

    /**
     * Gets as evaluationData
     *
     * Оценочные данные (0 – признак не установлен; 1 – признак установлен)
     *
     * @return boolean
     */
    public function getEvaluationData()
    {
        return $this->evaluationData;
    }

    /**
     * Sets a new evaluationData
     *
     * Оценочные данные (0 – признак не установлен; 1 – признак установлен)
     *
     * @param boolean $evaluationData
     * @return static
     */
    public function setEvaluationData($evaluationData)
    {
        $this->evaluationData = $evaluationData;
        return $this;
    }


}

