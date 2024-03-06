<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing CurrSellTransRaifType
 *
 *
 * XSD Type: CurrSellTransRaif
 */
class CurrSellTransRaifType
{

    /**
     * Сумма продаваемой валюты
     *
     * @property \common\models\raiffeisenxml\request\CurrSellTransRaifType\AmountSellAType $amountSell
     */
    private $amountSell = null;

    /**
     * Сумма рублей
     *
     * @property float $amountRu
     */
    private $amountRu = null;

    /**
     * Дата списания
     *
     * @property \DateTime $writeOffDate
     */
    private $writeOffDate = null;

    /**
     * Реквизиты текущего валютного счёта клиента
     *
     * @property \common\models\raiffeisenxml\request\AccountType $accDoc
     */
    private $accDoc = null;

    /**
     * Счет зачисления
     *
     * @property \common\models\raiffeisenxml\request\AccountType $enrollAcc
     */
    private $enrollAcc = null;

    /**
     * Тип сделки:
     *
     *  1 - По индивидуальным условиям
     *
     *  2 - По курсу банка\internal rate
     *
     * @property string $dealType
     */
    private $dealType = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @property \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Gets as amountSell
     *
     * Сумма продаваемой валюты
     *
     * @return \common\models\raiffeisenxml\request\CurrSellTransRaifType\AmountSellAType
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
     * @param \common\models\raiffeisenxml\request\CurrSellTransRaifType\AmountSellAType $amountSell
     * @return static
     */
    public function setAmountSell(\common\models\raiffeisenxml\request\CurrSellTransRaifType\AmountSellAType $amountSell)
    {
        $this->amountSell = $amountSell;
        return $this;
    }

    /**
     * Gets as amountRu
     *
     * Сумма рублей
     *
     * @return float
     */
    public function getAmountRu()
    {
        return $this->amountRu;
    }

    /**
     * Sets a new amountRu
     *
     * Сумма рублей
     *
     * @param float $amountRu
     * @return static
     */
    public function setAmountRu($amountRu)
    {
        $this->amountRu = $amountRu;
        return $this;
    }

    /**
     * Gets as writeOffDate
     *
     * Дата списания
     *
     * @return \DateTime
     */
    public function getWriteOffDate()
    {
        return $this->writeOffDate;
    }

    /**
     * Sets a new writeOffDate
     *
     * Дата списания
     *
     * @param \DateTime $writeOffDate
     * @return static
     */
    public function setWriteOffDate(\DateTime $writeOffDate)
    {
        $this->writeOffDate = $writeOffDate;
        return $this;
    }

    /**
     * Gets as accDoc
     *
     * Реквизиты текущего валютного счёта клиента
     *
     * @return \common\models\raiffeisenxml\request\AccountType
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
     * @param \common\models\raiffeisenxml\request\AccountType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\raiffeisenxml\request\AccountType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as enrollAcc
     *
     * Счет зачисления
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getEnrollAcc()
    {
        return $this->enrollAcc;
    }

    /**
     * Sets a new enrollAcc
     *
     * Счет зачисления
     *
     * @param \common\models\raiffeisenxml\request\AccountType $enrollAcc
     * @return static
     */
    public function setEnrollAcc(\common\models\raiffeisenxml\request\AccountType $enrollAcc)
    {
        $this->enrollAcc = $enrollAcc;
        return $this;
    }

    /**
     * Gets as dealType
     *
     * Тип сделки:
     *
     *  1 - По индивидуальным условиям
     *
     *  2 - По курсу банка\internal rate
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
     * Тип сделки:
     *
     *  1 - По индивидуальным условиям
     *
     *  2 - По курсу банка\internal rate
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
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLinkedDocs($index)
    {
        return isset($this->linkedDocs[$index]);
    }

    /**
     * unset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLinkedDocs($index)
    {
        unset($this->linkedDocs[$index]);
    }

    /**
     * Gets as linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[]
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }


}

