<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CardDepositsType
 *
 * Список карточек депозита
 * XSD Type: CardDeposits
 */
class CardDepositsType
{

    /**
     * Идентификатор последнего обновления справочника
     *
     * @property integer $stepId
     */
    private $stepId = null;

    /**
     * Карточка депозита
     *
     * @property \common\models\sbbolxml\response\CardDepositType[] $cardDeposit
     */
    private $cardDeposit = array(
        
    );

    /**
     * Gets as stepId
     *
     * Идентификатор последнего обновления справочника
     *
     * @return integer
     */
    public function getStepId()
    {
        return $this->stepId;
    }

    /**
     * Sets a new stepId
     *
     * Идентификатор последнего обновления справочника
     *
     * @param integer $stepId
     * @return static
     */
    public function setStepId($stepId)
    {
        $this->stepId = $stepId;
        return $this;
    }

    /**
     * Adds as cardDeposit
     *
     * Карточка депозита
     *
     * @return static
     * @param \common\models\sbbolxml\response\CardDepositType $cardDeposit
     */
    public function addToCardDeposit(\common\models\sbbolxml\response\CardDepositType $cardDeposit)
    {
        $this->cardDeposit[] = $cardDeposit;
        return $this;
    }

    /**
     * isset cardDeposit
     *
     * Карточка депозита
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCardDeposit($index)
    {
        return isset($this->cardDeposit[$index]);
    }

    /**
     * unset cardDeposit
     *
     * Карточка депозита
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCardDeposit($index)
    {
        unset($this->cardDeposit[$index]);
    }

    /**
     * Gets as cardDeposit
     *
     * Карточка депозита
     *
     * @return \common\models\sbbolxml\response\CardDepositType[]
     */
    public function getCardDeposit()
    {
        return $this->cardDeposit;
    }

    /**
     * Sets a new cardDeposit
     *
     * Карточка депозита
     *
     * @param \common\models\sbbolxml\response\CardDepositType[] $cardDeposit
     * @return static
     */
    public function setCardDeposit(array $cardDeposit)
    {
        $this->cardDeposit = $cardDeposit;
        return $this;
    }


}

