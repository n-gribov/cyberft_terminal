<?php

namespace common\models\raiffeisenxml\request\DealPassCred138IRaifType\HelpInfoAType;

/**
 * Class representing PaymentsAType
 */
class PaymentsAType
{

    /**
     * 9.1 Основания для заполнения п.9.2
     *
     *  9.1.1 Сведения из кредитного договора
     *  9.1.2 Оценочные вседения
     *
     * @property string $ground
     */
    private $ground = null;

    /**
     * 9.2 Описание графика платежей по возврату основного долга
     *  и процентных платежей
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IRaifType\HelpInfoAType\PaymentsAType\ReturnAType[] $return
     */
    private $return = [
        
    ];

    /**
     * Gets as ground
     *
     * 9.1 Основания для заполнения п.9.2
     *
     *  9.1.1 Сведения из кредитного договора
     *  9.1.2 Оценочные вседения
     *
     * @return string
     */
    public function getGround()
    {
        return $this->ground;
    }

    /**
     * Sets a new ground
     *
     * 9.1 Основания для заполнения п.9.2
     *
     *  9.1.1 Сведения из кредитного договора
     *  9.1.2 Оценочные вседения
     *
     * @param string $ground
     * @return static
     */
    public function setGround($ground)
    {
        $this->ground = $ground;
        return $this;
    }

    /**
     * Adds as return
     *
     * 9.2 Описание графика платежей по возврату основного долга
     *  и процентных платежей
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\DealPassCred138IRaifType\HelpInfoAType\PaymentsAType\ReturnAType $return
     */
    public function addToReturn(\common\models\raiffeisenxml\request\DealPassCred138IRaifType\HelpInfoAType\PaymentsAType\ReturnAType $return)
    {
        $this->return[] = $return;
        return $this;
    }

    /**
     * isset return
     *
     * 9.2 Описание графика платежей по возврату основного долга
     *  и процентных платежей
     *
     * @param int|string $index
     * @return bool
     */
    public function issetReturn($index)
    {
        return isset($this->return[$index]);
    }

    /**
     * unset return
     *
     * 9.2 Описание графика платежей по возврату основного долга
     *  и процентных платежей
     *
     * @param int|string $index
     * @return void
     */
    public function unsetReturn($index)
    {
        unset($this->return[$index]);
    }

    /**
     * Gets as return
     *
     * 9.2 Описание графика платежей по возврату основного долга
     *  и процентных платежей
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IRaifType\HelpInfoAType\PaymentsAType\ReturnAType[]
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * Sets a new return
     *
     * 9.2 Описание графика платежей по возврату основного долга
     *  и процентных платежей
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IRaifType\HelpInfoAType\PaymentsAType\ReturnAType[] $return
     * @return static
     */
    public function setReturn(array $return)
    {
        $this->return = $return;
        return $this;
    }


}

