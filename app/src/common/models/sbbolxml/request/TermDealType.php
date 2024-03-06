<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing TermDealType
 *
 *
 * XSD Type: TermDeal
 */
class TermDealType
{

    /**
     * Условия сделки: 2 - по курсу Банка России с взиманием комиссионного
     *  вознаграждения, 1 - по курсу Сбербанка
     *  России, 0 - на бирже на торговой сессии
     *
     * @property string $dealType
     */
    private $dealType = null;

    /**
     * 0 - USD-RUB, 1 - EUR-RUB, 2- EUR-USD
     *
     * @property string $uSDEUR
     */
    private $uSDEUR = null;

    /**
     * 0 - TOD, 1 - TOM
     *
     * @property string $tODTOM
     */
    private $tODTOM = null;

    /**
     * на бирже на тороговой сессии по курсу
     *
     * @property \common\models\sbbolxml\request\TermDealType\RateAType $rate
     */
    private $rate = null;

    /**
     * Gets as dealType
     *
     * Условия сделки: 2 - по курсу Банка России с взиманием комиссионного
     *  вознаграждения, 1 - по курсу Сбербанка
     *  России, 0 - на бирже на торговой сессии
     *
     * @return string
     */
    public function getDealType()
    {
        return $this->dealType;
    }

    /**
     * Sets a new dealType
     *
     * Условия сделки: 2 - по курсу Банка России с взиманием комиссионного
     *  вознаграждения, 1 - по курсу Сбербанка
     *  России, 0 - на бирже на торговой сессии
     *
     * @param string $dealType
     * @return static
     */
    public function setDealType($dealType)
    {
        $this->dealType = $dealType;
        return $this;
    }

    /**
     * Gets as uSDEUR
     *
     * 0 - USD-RUB, 1 - EUR-RUB, 2- EUR-USD
     *
     * @return string
     */
    public function getUSDEUR()
    {
        return $this->uSDEUR;
    }

    /**
     * Sets a new uSDEUR
     *
     * 0 - USD-RUB, 1 - EUR-RUB, 2- EUR-USD
     *
     * @param string $uSDEUR
     * @return static
     */
    public function setUSDEUR($uSDEUR)
    {
        $this->uSDEUR = $uSDEUR;
        return $this;
    }

    /**
     * Gets as tODTOM
     *
     * 0 - TOD, 1 - TOM
     *
     * @return string
     */
    public function getTODTOM()
    {
        return $this->tODTOM;
    }

    /**
     * Sets a new tODTOM
     *
     * 0 - TOD, 1 - TOM
     *
     * @param string $tODTOM
     * @return static
     */
    public function setTODTOM($tODTOM)
    {
        $this->tODTOM = $tODTOM;
        return $this;
    }

    /**
     * Gets as rate
     *
     * на бирже на тороговой сессии по курсу
     *
     * @return \common\models\sbbolxml\request\TermDealType\RateAType
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Sets a new rate
     *
     * на бирже на тороговой сессии по курсу
     *
     * @param \common\models\sbbolxml\request\TermDealType\RateAType $rate
     * @return static
     */
    public function setRate(\common\models\sbbolxml\request\TermDealType\RateAType $rate)
    {
        $this->rate = $rate;
        return $this;
    }


}

