<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CartotecaDocType
 *
 *
 * XSD Type: CartotecaDoc
 */
class CartotecaDocType
{

    /**
     * Содержит информацию о расчетных документах, ожидающих разрешения на проведение
     *  операции
     *
     * @property \common\models\sbbolxml\response\CartotecaDocType\PermitionOperDocsAType $permitionOperDocs
     */
    private $permitionOperDocs = null;

    /**
     * Содержит информацию о расчетных документах, ожидающих акцепта
     *
     * @property \common\models\sbbolxml\response\CartotecaDocType\AcceptDocsAType $acceptDocs
     */
    private $acceptDocs = null;

    /**
     * Содержит информацию о расчетных документах, помещённых в картотеку к счету 90902
     *  (картотека 2)
     *
     * @property \common\models\sbbolxml\response\CartotecaDocType\Cartoteca2AType $cartoteca2
     */
    private $cartoteca2 = null;

    /**
     * Документы, ожидающих разрешения на проведение операции в рублях РФ (картотека 1) –
     *  Только для валютных счетов.
     *
     * @property \common\models\sbbolxml\response\CartotecaDocType\OperAcceptAType $operAccept
     */
    private $operAccept = null;

    /**
     * Документы, помещённые в картотеку из-за отсутствия\недостаточности денежных
     *  средств на счете клиента в рублях РФ(картотека 2) – Только для валютных счетов.
     *
     * @property \common\models\sbbolxml\response\CartotecaDocType\NoMoneyAType $noMoney
     */
    private $noMoney = null;

    /**
     * Gets as permitionOperDocs
     *
     * Содержит информацию о расчетных документах, ожидающих разрешения на проведение
     *  операции
     *
     * @return \common\models\sbbolxml\response\CartotecaDocType\PermitionOperDocsAType
     */
    public function getPermitionOperDocs()
    {
        return $this->permitionOperDocs;
    }

    /**
     * Sets a new permitionOperDocs
     *
     * Содержит информацию о расчетных документах, ожидающих разрешения на проведение
     *  операции
     *
     * @param \common\models\sbbolxml\response\CartotecaDocType\PermitionOperDocsAType $permitionOperDocs
     * @return static
     */
    public function setPermitionOperDocs(\common\models\sbbolxml\response\CartotecaDocType\PermitionOperDocsAType $permitionOperDocs)
    {
        $this->permitionOperDocs = $permitionOperDocs;
        return $this;
    }

    /**
     * Gets as acceptDocs
     *
     * Содержит информацию о расчетных документах, ожидающих акцепта
     *
     * @return \common\models\sbbolxml\response\CartotecaDocType\AcceptDocsAType
     */
    public function getAcceptDocs()
    {
        return $this->acceptDocs;
    }

    /**
     * Sets a new acceptDocs
     *
     * Содержит информацию о расчетных документах, ожидающих акцепта
     *
     * @param \common\models\sbbolxml\response\CartotecaDocType\AcceptDocsAType $acceptDocs
     * @return static
     */
    public function setAcceptDocs(\common\models\sbbolxml\response\CartotecaDocType\AcceptDocsAType $acceptDocs)
    {
        $this->acceptDocs = $acceptDocs;
        return $this;
    }

    /**
     * Gets as cartoteca2
     *
     * Содержит информацию о расчетных документах, помещённых в картотеку к счету 90902
     *  (картотека 2)
     *
     * @return \common\models\sbbolxml\response\CartotecaDocType\Cartoteca2AType
     */
    public function getCartoteca2()
    {
        return $this->cartoteca2;
    }

    /**
     * Sets a new cartoteca2
     *
     * Содержит информацию о расчетных документах, помещённых в картотеку к счету 90902
     *  (картотека 2)
     *
     * @param \common\models\sbbolxml\response\CartotecaDocType\Cartoteca2AType $cartoteca2
     * @return static
     */
    public function setCartoteca2(\common\models\sbbolxml\response\CartotecaDocType\Cartoteca2AType $cartoteca2)
    {
        $this->cartoteca2 = $cartoteca2;
        return $this;
    }

    /**
     * Gets as operAccept
     *
     * Документы, ожидающих разрешения на проведение операции в рублях РФ (картотека 1) –
     *  Только для валютных счетов.
     *
     * @return \common\models\sbbolxml\response\CartotecaDocType\OperAcceptAType
     */
    public function getOperAccept()
    {
        return $this->operAccept;
    }

    /**
     * Sets a new operAccept
     *
     * Документы, ожидающих разрешения на проведение операции в рублях РФ (картотека 1) –
     *  Только для валютных счетов.
     *
     * @param \common\models\sbbolxml\response\CartotecaDocType\OperAcceptAType $operAccept
     * @return static
     */
    public function setOperAccept(\common\models\sbbolxml\response\CartotecaDocType\OperAcceptAType $operAccept)
    {
        $this->operAccept = $operAccept;
        return $this;
    }

    /**
     * Gets as noMoney
     *
     * Документы, помещённые в картотеку из-за отсутствия\недостаточности денежных
     *  средств на счете клиента в рублях РФ(картотека 2) – Только для валютных счетов.
     *
     * @return \common\models\sbbolxml\response\CartotecaDocType\NoMoneyAType
     */
    public function getNoMoney()
    {
        return $this->noMoney;
    }

    /**
     * Sets a new noMoney
     *
     * Документы, помещённые в картотеку из-за отсутствия\недостаточности денежных
     *  средств на счете клиента в рублях РФ(картотека 2) – Только для валютных счетов.
     *
     * @param \common\models\sbbolxml\response\CartotecaDocType\NoMoneyAType $noMoney
     * @return static
     */
    public function setNoMoney(\common\models\sbbolxml\response\CartotecaDocType\NoMoneyAType $noMoney)
    {
        $this->noMoney = $noMoney;
        return $this;
    }


}

