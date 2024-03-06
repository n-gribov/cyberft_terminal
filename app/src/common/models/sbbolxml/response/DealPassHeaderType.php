<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DealPassHeaderType
 *
 * Заголовок паспорта сделки
 * XSD Type: DealPassHeader
 */
class DealPassHeaderType
{

    /**
     * Основные данные ПС по контракту
     *
     * @property \common\models\sbbolxml\response\DealPassNumType $dealPassNum
     */
    private $dealPassNum = null;

    /**
     * Gets as dealPassNum
     *
     * Основные данные ПС по контракту
     *
     * @return \common\models\sbbolxml\response\DealPassNumType
     */
    public function getDealPassNum()
    {
        return $this->dealPassNum;
    }

    /**
     * Sets a new dealPassNum
     *
     * Основные данные ПС по контракту
     *
     * @param \common\models\sbbolxml\response\DealPassNumType $dealPassNum
     * @return static
     */
    public function setDealPassNum(\common\models\sbbolxml\response\DealPassNumType $dealPassNum)
    {
        $this->dealPassNum = $dealPassNum;
        return $this;
    }


}

