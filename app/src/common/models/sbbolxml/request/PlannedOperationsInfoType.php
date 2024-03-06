<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PlannedOperationsInfoType
 *
 * Сведения о планируемых операциях по счету в месяц
 * XSD Type: PlannedOperationsInfo
 */
class PlannedOperationsInfoType
{

    /**
     * Количество планируемых операций по счету в месяц
     *  Значение Расшифровка
     *  1 От 10 до 100 включительно
     *  2 Свыше 100 до 1000 включительно
     *  3 Свыше 1000
     *
     * @property string $plannedOperationsQuantity
     */
    private $plannedOperationsQuantity = null;

    /**
     * Сумма планируемых операций (предполагаемые обороты по счету в месяц) включая операции по снятию денежных средств в
     *  наличной форме и операции, связанные с переводами денежных средств в рамках внешнеторговой деятельности в рублевом эквиваленте
     *  Значение Расшифровка
     *  1 До 600 000 рублей включительно
     *  2 Свыше 600 000 до 10 000 000 рублей включительно
     *  3 Свыше 10 000 000 до 100 000 000 рублей включительно
     *  4 Свыше 100 000 000 рублей
     *
     * @property string $plannedOperationsSum
     */
    private $plannedOperationsSum = null;

    /**
     * Gets as plannedOperationsQuantity
     *
     * Количество планируемых операций по счету в месяц
     *  Значение Расшифровка
     *  1 От 10 до 100 включительно
     *  2 Свыше 100 до 1000 включительно
     *  3 Свыше 1000
     *
     * @return string
     */
    public function getPlannedOperationsQuantity()
    {
        return $this->plannedOperationsQuantity;
    }

    /**
     * Sets a new plannedOperationsQuantity
     *
     * Количество планируемых операций по счету в месяц
     *  Значение Расшифровка
     *  1 От 10 до 100 включительно
     *  2 Свыше 100 до 1000 включительно
     *  3 Свыше 1000
     *
     * @param string $plannedOperationsQuantity
     * @return static
     */
    public function setPlannedOperationsQuantity($plannedOperationsQuantity)
    {
        $this->plannedOperationsQuantity = $plannedOperationsQuantity;
        return $this;
    }

    /**
     * Gets as plannedOperationsSum
     *
     * Сумма планируемых операций (предполагаемые обороты по счету в месяц) включая операции по снятию денежных средств в
     *  наличной форме и операции, связанные с переводами денежных средств в рамках внешнеторговой деятельности в рублевом эквиваленте
     *  Значение Расшифровка
     *  1 До 600 000 рублей включительно
     *  2 Свыше 600 000 до 10 000 000 рублей включительно
     *  3 Свыше 10 000 000 до 100 000 000 рублей включительно
     *  4 Свыше 100 000 000 рублей
     *
     * @return string
     */
    public function getPlannedOperationsSum()
    {
        return $this->plannedOperationsSum;
    }

    /**
     * Sets a new plannedOperationsSum
     *
     * Сумма планируемых операций (предполагаемые обороты по счету в месяц) включая операции по снятию денежных средств в
     *  наличной форме и операции, связанные с переводами денежных средств в рамках внешнеторговой деятельности в рублевом эквиваленте
     *  Значение Расшифровка
     *  1 До 600 000 рублей включительно
     *  2 Свыше 600 000 до 10 000 000 рублей включительно
     *  3 Свыше 10 000 000 до 100 000 000 рублей включительно
     *  4 Свыше 100 000 000 рублей
     *
     * @param string $plannedOperationsSum
     * @return static
     */
    public function setPlannedOperationsSum($plannedOperationsSum)
    {
        $this->plannedOperationsSum = $plannedOperationsSum;
        return $this;
    }


}

