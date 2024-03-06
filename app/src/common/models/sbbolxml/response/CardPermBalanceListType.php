<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CardPermBalanceListType
 *
 * Список договоров НСО
 * XSD Type: CardPermBalanceList
 */
class CardPermBalanceListType
{

    /**
     * Идентификатор последнего обновления справочника
     *
     * @property integer $stepId
     */
    private $stepId = null;

    /**
     * Карточка НСО
     *
     * @property \common\models\sbbolxml\response\CardPermBalanceType[] $cardPermBalance
     */
    private $cardPermBalance = array(
        
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
     * Adds as cardPermBalance
     *
     * Карточка НСО
     *
     * @return static
     * @param \common\models\sbbolxml\response\CardPermBalanceType $cardPermBalance
     */
    public function addToCardPermBalance(\common\models\sbbolxml\response\CardPermBalanceType $cardPermBalance)
    {
        $this->cardPermBalance[] = $cardPermBalance;
        return $this;
    }

    /**
     * isset cardPermBalance
     *
     * Карточка НСО
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCardPermBalance($index)
    {
        return isset($this->cardPermBalance[$index]);
    }

    /**
     * unset cardPermBalance
     *
     * Карточка НСО
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCardPermBalance($index)
    {
        unset($this->cardPermBalance[$index]);
    }

    /**
     * Gets as cardPermBalance
     *
     * Карточка НСО
     *
     * @return \common\models\sbbolxml\response\CardPermBalanceType[]
     */
    public function getCardPermBalance()
    {
        return $this->cardPermBalance;
    }

    /**
     * Sets a new cardPermBalance
     *
     * Карточка НСО
     *
     * @param \common\models\sbbolxml\response\CardPermBalanceType[] $cardPermBalance
     * @return static
     */
    public function setCardPermBalance(array $cardPermBalance)
    {
        $this->cardPermBalance = $cardPermBalance;
        return $this;
    }


}

