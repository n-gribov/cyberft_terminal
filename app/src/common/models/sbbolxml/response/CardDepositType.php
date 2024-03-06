<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CardDepositType
 *
 * Карточка депозита
 * XSD Type: CardDeposit
 */
class CardDepositType
{

    /**
     * Идентификатор сущности
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Данные карточки депозита
     *
     * @property \common\models\sbbolxml\response\CardDepositType\CardDepositInfoAType $cardDepositInfo
     */
    private $cardDepositInfo = null;

    /**
     * Gets as docId
     *
     * Идентификатор сущности
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор сущности
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as cardDepositInfo
     *
     * Данные карточки депозита
     *
     * @return \common\models\sbbolxml\response\CardDepositType\CardDepositInfoAType
     */
    public function getCardDepositInfo()
    {
        return $this->cardDepositInfo;
    }

    /**
     * Sets a new cardDepositInfo
     *
     * Данные карточки депозита
     *
     * @param \common\models\sbbolxml\response\CardDepositType\CardDepositInfoAType $cardDepositInfo
     * @return static
     */
    public function setCardDepositInfo(\common\models\sbbolxml\response\CardDepositType\CardDepositInfoAType $cardDepositInfo)
    {
        $this->cardDepositInfo = $cardDepositInfo;
        return $this;
    }


}

