<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType;

/**
 * Class representing GuaranteeAType
 */
class GuaranteeAType
{

    /**
     * Способ выпуска. Возможные значения: "на бланке", "по системе SWIFT".
     *
     * @property string $process
     */
    private $process = null;

    /**
     * SWIFT код
     *
     * @property string $swift
     */
    private $swift = null;

    /**
     * Сумма и валюта гарантии
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType\SumAType $sum
     */
    private $sum = null;

    /**
     * Тип гарантии. Возможные значения: "Гарантии платежа, включая гарантии в пользу налоговых органов и PAP", "Гарантия в пользу таможенных органов", "Гарантия возврата аванса", "Гарантия возврата кредита", "Гарантия исполнения контракта", "Гарантия на гарантийный период", "Контргарантия", "Тендерная гарантия".
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Действительна
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType\ValidAType $valid
     */
    private $valid = null;

    /**
     * Gets as process
     *
     * Способ выпуска. Возможные значения: "на бланке", "по системе SWIFT".
     *
     * @return string
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * Sets a new process
     *
     * Способ выпуска. Возможные значения: "на бланке", "по системе SWIFT".
     *
     * @param string $process
     * @return static
     */
    public function setProcess($process)
    {
        $this->process = $process;
        return $this;
    }

    /**
     * Gets as swift
     *
     * SWIFT код
     *
     * @return string
     */
    public function getSwift()
    {
        return $this->swift;
    }

    /**
     * Sets a new swift
     *
     * SWIFT код
     *
     * @param string $swift
     * @return static
     */
    public function setSwift($swift)
    {
        $this->swift = $swift;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма и валюта гарантии
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType\SumAType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма и валюта гарантии
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType\SumAType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType\SumAType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as type
     *
     * Тип гарантии. Возможные значения: "Гарантии платежа, включая гарантии в пользу налоговых органов и PAP", "Гарантия в пользу таможенных органов", "Гарантия возврата аванса", "Гарантия возврата кредита", "Гарантия исполнения контракта", "Гарантия на гарантийный период", "Контргарантия", "Тендерная гарантия".
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип гарантии. Возможные значения: "Гарантии платежа, включая гарантии в пользу налоговых органов и PAP", "Гарантия в пользу таможенных органов", "Гарантия возврата аванса", "Гарантия возврата кредита", "Гарантия исполнения контракта", "Гарантия на гарантийный период", "Контргарантия", "Тендерная гарантия".
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as valid
     *
     * Действительна
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType\ValidAType
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Sets a new valid
     *
     * Действительна
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType\ValidAType $valid
     * @return static
     */
    public function setValid(\common\models\raiffeisenxml\request\GuaranteeRaifType\GuaranteeAType\ValidAType $valid)
    {
        $this->valid = $valid;
        return $this;
    }


}

