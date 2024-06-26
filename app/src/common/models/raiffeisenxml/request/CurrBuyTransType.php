<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing CurrBuyTransType
 *
 *
 * XSD Type: CurrBuyTrans
 */
class CurrBuyTransType
{

    /**
     * Реквизиты текущего валютного счёта клиента
     *
     * @property \common\models\raiffeisenxml\request\AccountRUType $accDoc
     */
    private $accDoc = null;

    /**
     * Счет банка, на кот. перечислены средства для продажи
     *
     * @property \common\models\raiffeisenxml\request\AccountRUType $accBank
     */
    private $accBank = null;

    /**
     * Реквизиты платежного поручения
     *
     * @property \common\models\raiffeisenxml\request\DocDataType $payDocRu
     */
    private $payDocRu = null;

    /**
     * Реквизиты счёта зачисления купленной валюты
     *
     * @property \common\models\raiffeisenxml\request\CurrBuyTransType\AccTransAType $accTrans
     */
    private $accTrans = null;

    /**
     * Сумма продаваемой валюты
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $amountSell
     */
    private $amountSell = null;

    /**
     * Сумма покупаемой валюты
     *
     * @property \common\models\raiffeisenxml\request\CurrAmountType $amountBuy
     */
    private $amountBuy = null;

    /**
     * Тип сделки
     *  Покупка по коммерческому курсу банка - 01
     *  Покупка по курсу биржи - 02
     *  Покупка валюты на межбанковском рынке по заданному курсу - 03
     *  Покупка валюты по курсу ЦБ - 04
     *
     * @property string $dealType
     */
    private $dealType = null;

    /**
     * Курс "не менее", по которому требуется продать валюту. Либо "не более", по кот.
     *  нужно купить валюту
     *  Заполняется, если биржа
     *
     * @property float $rateCurs
     */
    private $rateCurs = null;

    /**
     * Условия сделки
     *
     * @property \common\models\raiffeisenxml\request\TermsType $terms
     */
    private $terms = null;

    /**
     * Ком. вознаграждение списать с нашего счета
     *
     * @property \common\models\raiffeisenxml\request\AccountRUType $accCom
     */
    private $accCom = null;

    /**
     * Удержать из суммы сделки
     *
     * @property bool $amount
     */
    private $amount = null;

    /**
     * Перечислены платежным поручением
     *
     * @property \common\models\raiffeisenxml\request\DocDataType $payDocRuComis
     */
    private $payDocRuComis = null;

    /**
     * Gets as accDoc
     *
     * Реквизиты текущего валютного счёта клиента
     *
     * @return \common\models\raiffeisenxml\request\AccountRUType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Реквизиты текущего валютного счёта клиента
     *
     * @param \common\models\raiffeisenxml\request\AccountRUType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\raiffeisenxml\request\AccountRUType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as accBank
     *
     * Счет банка, на кот. перечислены средства для продажи
     *
     * @return \common\models\raiffeisenxml\request\AccountRUType
     */
    public function getAccBank()
    {
        return $this->accBank;
    }

    /**
     * Sets a new accBank
     *
     * Счет банка, на кот. перечислены средства для продажи
     *
     * @param \common\models\raiffeisenxml\request\AccountRUType $accBank
     * @return static
     */
    public function setAccBank(\common\models\raiffeisenxml\request\AccountRUType $accBank)
    {
        $this->accBank = $accBank;
        return $this;
    }

    /**
     * Gets as payDocRu
     *
     * Реквизиты платежного поручения
     *
     * @return \common\models\raiffeisenxml\request\DocDataType
     */
    public function getPayDocRu()
    {
        return $this->payDocRu;
    }

    /**
     * Sets a new payDocRu
     *
     * Реквизиты платежного поручения
     *
     * @param \common\models\raiffeisenxml\request\DocDataType $payDocRu
     * @return static
     */
    public function setPayDocRu(\common\models\raiffeisenxml\request\DocDataType $payDocRu)
    {
        $this->payDocRu = $payDocRu;
        return $this;
    }

    /**
     * Gets as accTrans
     *
     * Реквизиты счёта зачисления купленной валюты
     *
     * @return \common\models\raiffeisenxml\request\CurrBuyTransType\AccTransAType
     */
    public function getAccTrans()
    {
        return $this->accTrans;
    }

    /**
     * Sets a new accTrans
     *
     * Реквизиты счёта зачисления купленной валюты
     *
     * @param \common\models\raiffeisenxml\request\CurrBuyTransType\AccTransAType $accTrans
     * @return static
     */
    public function setAccTrans(\common\models\raiffeisenxml\request\CurrBuyTransType\AccTransAType $accTrans)
    {
        $this->accTrans = $accTrans;
        return $this;
    }

    /**
     * Gets as amountSell
     *
     * Сумма продаваемой валюты
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getAmountSell()
    {
        return $this->amountSell;
    }

    /**
     * Sets a new amountSell
     *
     * Сумма продаваемой валюты
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $amountSell
     * @return static
     */
    public function setAmountSell(\common\models\raiffeisenxml\request\CurrAmountType $amountSell)
    {
        $this->amountSell = $amountSell;
        return $this;
    }

    /**
     * Gets as amountBuy
     *
     * Сумма покупаемой валюты
     *
     * @return \common\models\raiffeisenxml\request\CurrAmountType
     */
    public function getAmountBuy()
    {
        return $this->amountBuy;
    }

    /**
     * Sets a new amountBuy
     *
     * Сумма покупаемой валюты
     *
     * @param \common\models\raiffeisenxml\request\CurrAmountType $amountBuy
     * @return static
     */
    public function setAmountBuy(\common\models\raiffeisenxml\request\CurrAmountType $amountBuy)
    {
        $this->amountBuy = $amountBuy;
        return $this;
    }

    /**
     * Gets as dealType
     *
     * Тип сделки
     *  Покупка по коммерческому курсу банка - 01
     *  Покупка по курсу биржи - 02
     *  Покупка валюты на межбанковском рынке по заданному курсу - 03
     *  Покупка валюты по курсу ЦБ - 04
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
     * Тип сделки
     *  Покупка по коммерческому курсу банка - 01
     *  Покупка по курсу биржи - 02
     *  Покупка валюты на межбанковском рынке по заданному курсу - 03
     *  Покупка валюты по курсу ЦБ - 04
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
     * Gets as rateCurs
     *
     * Курс "не менее", по которому требуется продать валюту. Либо "не более", по кот.
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
     * Курс "не менее", по которому требуется продать валюту. Либо "не более", по кот.
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
     * Условия сделки
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
     * Условия сделки
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
     * Gets as accCom
     *
     * Ком. вознаграждение списать с нашего счета
     *
     * @return \common\models\raiffeisenxml\request\AccountRUType
     */
    public function getAccCom()
    {
        return $this->accCom;
    }

    /**
     * Sets a new accCom
     *
     * Ком. вознаграждение списать с нашего счета
     *
     * @param \common\models\raiffeisenxml\request\AccountRUType $accCom
     * @return static
     */
    public function setAccCom(\common\models\raiffeisenxml\request\AccountRUType $accCom)
    {
        $this->accCom = $accCom;
        return $this;
    }

    /**
     * Gets as amount
     *
     * Удержать из суммы сделки
     *
     * @return bool
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets a new amount
     *
     * Удержать из суммы сделки
     *
     * @param bool $amount
     * @return static
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Gets as payDocRuComis
     *
     * Перечислены платежным поручением
     *
     * @return \common\models\raiffeisenxml\request\DocDataType
     */
    public function getPayDocRuComis()
    {
        return $this->payDocRuComis;
    }

    /**
     * Sets a new payDocRuComis
     *
     * Перечислены платежным поручением
     *
     * @param \common\models\raiffeisenxml\request\DocDataType $payDocRuComis
     * @return static
     */
    public function setPayDocRuComis(\common\models\raiffeisenxml\request\DocDataType $payDocRuComis)
    {
        $this->payDocRuComis = $payDocRuComis;
        return $this;
    }


}

