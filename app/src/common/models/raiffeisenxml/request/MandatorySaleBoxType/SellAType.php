<?php

namespace common\models\raiffeisenxml\request\MandatorySaleBoxType;

/**
 * Class representing SellAType
 */
class SellAType
{

    /**
     * Сумма для продажи
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $sum
     */
    private $sum = null;

    /**
     * Продажа по коммерческому курсу банка - 01
     *  Продажа по курсу биржи - 02
     *  Продажа валюты на межбанковском рынке по заданному курсу - 03
     *  Продажа валюты по курсу ЦБ - 04
     *
     * @property string $dealType
     */
    private $dealType = null;

    /**
     * Реквизиты счета зачисления рублей (номер счета б. заполнен)
     *
     * @property \common\models\raiffeisenxml\request\AccountRUType $acc
     */
    private $acc = null;

    /**
     * Код вида валютной операции
     *
     * @property string $vo
     */
    private $vo = null;

    /**
     * 0 - курс банка
     *  1 - не менее
     *
     * @property bool $rateType
     */
    private $rateType = null;

    /**
     * Курс "не менее", по которому требуется продать валюту. Либо "не
     *  более", по кот.
     *  нужно купить валюту
     *  Заполняется, если биржа
     *
     * @property float $rateCurs
     */
    private $rateCurs = null;

    /**
     * Условия поставки рублей/валюты
     *
     * @property \common\models\raiffeisenxml\request\TermsType $terms
     */
    private $terms = null;

    /**
     * Процент суммы для обязательной продажи
     *
     * @property float $percent
     */
    private $percent = null;

    /**
     * Gets as sum
     *
     * Сумма для продажи
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма для продажи
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\CurrAmountType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as dealType
     *
     * Продажа по коммерческому курсу банка - 01
     *  Продажа по курсу биржи - 02
     *  Продажа валюты на межбанковском рынке по заданному курсу - 03
     *  Продажа валюты по курсу ЦБ - 04
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
     * Продажа по коммерческому курсу банка - 01
     *  Продажа по курсу биржи - 02
     *  Продажа валюты на межбанковском рынке по заданному курсу - 03
     *  Продажа валюты по курсу ЦБ - 04
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
     * Gets as acc
     *
     * Реквизиты счета зачисления рублей (номер счета б. заполнен)
     *
     * @return \common\models\raiffeisenxml\request\AccountRUType
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * Реквизиты счета зачисления рублей (номер счета б. заполнен)
     *
     * @param \common\models\raiffeisenxml\request\AccountRUType $acc
     * @return static
     */
    public function setAcc(\common\models\raiffeisenxml\request\AccountRUType $acc)
    {
        $this->acc = $acc;
        return $this;
    }

    /**
     * Gets as vo
     *
     * Код вида валютной операции
     *
     * @return string
     */
    public function getVo()
    {
        return $this->vo;
    }

    /**
     * Sets a new vo
     *
     * Код вида валютной операции
     *
     * @param string $vo
     * @return static
     */
    public function setVo($vo)
    {
        $this->vo = $vo;
        return $this;
    }

    /**
     * Gets as rateType
     *
     * 0 - курс банка
     *  1 - не менее
     *
     * @return bool
     */
    public function getRateType()
    {
        return $this->rateType;
    }

    /**
     * Sets a new rateType
     *
     * 0 - курс банка
     *  1 - не менее
     *
     * @param bool $rateType
     * @return static
     */
    public function setRateType($rateType)
    {
        $this->rateType = $rateType;
        return $this;
    }

    /**
     * Gets as rateCurs
     *
     * Курс "не менее", по которому требуется продать валюту. Либо "не
     *  более", по кот.
     *  нужно купить валюту
     *  Заполняется, если биржа
     *
     * @return float
     */
    public function getRateCurs()
    {
        return $this->rateCurs;
    }

    /**
     * Sets a new rateCurs
     *
     * Курс "не менее", по которому требуется продать валюту. Либо "не
     *  более", по кот.
     *  нужно купить валюту
     *  Заполняется, если биржа
     *
     * @param float $rateCurs
     * @return static
     */
    public function setRateCurs($rateCurs)
    {
        $this->rateCurs = $rateCurs;
        return $this;
    }

    /**
     * Gets as terms
     *
     * Условия поставки рублей/валюты
     *
     * @return \common\models\raiffeisenxml\request\TermsType
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * Sets a new terms
     *
     * Условия поставки рублей/валюты
     *
     * @param \common\models\raiffeisenxml\request\TermsType $terms
     * @return static
     */
    public function setTerms(\common\models\raiffeisenxml\request\TermsType $terms)
    {
        $this->terms = $terms;
        return $this;
    }

    /**
     * Gets as percent
     *
     * Процент суммы для обязательной продажи
     *
     * @return float
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Sets a new percent
     *
     * Процент суммы для обязательной продажи
     *
     * @param float $percent
     * @return static
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }


}

