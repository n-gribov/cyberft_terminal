<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CardPermBalanceType
 *
 * Карточка депозита
 * XSD Type: CardPermBalance
 */
class CardPermBalanceType
{

    /**
     * Идентификатор сущности
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Данные карточки НСО
     *
     * @property \common\models\sbbolxml\response\CardPermBalanceType\CardPermBalanceInfoAType $cardPermBalanceInfo
     */
    private $cardPermBalanceInfo = null;

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
     * Gets as cardPermBalanceInfo
     *
     * Данные карточки НСО
     *
     * @return \common\models\sbbolxml\response\CardPermBalanceType\CardPermBalanceInfoAType
     */
    public function getCardPermBalanceInfo()
    {
        return $this->cardPermBalanceInfo;
    }

    /**
     * Sets a new cardPermBalanceInfo
     *
     * Данные карточки НСО
     *
     * @param \common\models\sbbolxml\response\CardPermBalanceType\CardPermBalanceInfoAType $cardPermBalanceInfo
     * @return static
     */
    public function setCardPermBalanceInfo(\common\models\sbbolxml\response\CardPermBalanceType\CardPermBalanceInfoAType $cardPermBalanceInfo)
    {
        $this->cardPermBalanceInfo = $cardPermBalanceInfo;
        return $this;
    }


}

