<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType\DealInfoAType\ContractInfoAType;

/**
 * Class representing GuaranteeReceiverAType
 */
class GuaranteeReceiverAType
{

    /**
     * Получатель. Возможные значения: "Бенефициара", "Следующего банка".
     *
     * @property string $receiver
     */
    private $receiver = null;

    /**
     * SWIFT банка получателя
     *
     * @property string $bankSwift
     */
    private $bankSwift = null;

    /**
     * Наименование банка получателя
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Gets as receiver
     *
     * Получатель. Возможные значения: "Бенефициара", "Следующего банка".
     *
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Sets a new receiver
     *
     * Получатель. Возможные значения: "Бенефициара", "Следующего банка".
     *
     * @param string $receiver
     * @return static
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Gets as bankSwift
     *
     * SWIFT банка получателя
     *
     * @return string
     */
    public function getBankSwift()
    {
        return $this->bankSwift;
    }

    /**
     * Sets a new bankSwift
     *
     * SWIFT банка получателя
     *
     * @param string $bankSwift
     * @return static
     */
    public function setBankSwift($bankSwift)
    {
        $this->bankSwift = $bankSwift;
        return $this;
    }

    /**
     * Gets as bankName
     *
     * Наименование банка получателя
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Наименование банка получателя
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }


}

