<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorpCardExtIssueRequestType
 *
 *
 * XSD Type: CorpCardExtIssueRequest
 */
class CorpCardExtIssueRequestType extends DocBaseType
{

    /**
     * Общие реквизиты документа ДБО
     *
     * @property \common\models\sbbolxml\request\DocDataType $docData
     */
    private $docData = null;

    /**
     * Реквизиты организации клиента
     *
     * @property \common\models\sbbolxml\request\CorpCardExtOrgDataType $orgData
     */
    private $orgData = null;

    /**
     * Общие реквизиты электронного документа
     *
     * @property \common\models\sbbolxml\request\CorpCardExtCommonDocType $commonDoc
     */
    private $commonDoc = null;

    /**
     * Реквизиты бизнес счёта клиента
     *
     * @property \common\models\sbbolxml\request\CorpCardExtBusinessAccNumBicType $account
     */
    private $account = null;

    /**
     * Список карт, которые требуется выпустить
     *
     * @property \common\models\sbbolxml\request\CorpCardHolderPersonInfoType[] $listCards
     */
    private $listCards = null;

    /**
     * Код продукта КК
     *
     * @property string $cardTypeCode
     */
    private $cardTypeCode = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты документа ДБО
     *
     * @return \common\models\sbbolxml\request\DocDataType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа ДБО
     *
     * @param \common\models\sbbolxml\request\DocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * Реквизиты организации клиента
     *
     * @return \common\models\sbbolxml\request\CorpCardExtOrgDataType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * Реквизиты организации клиента
     *
     * @param \common\models\sbbolxml\request\CorpCardExtOrgDataType $orgData
     * @return static
     */
    public function setOrgData(\common\models\sbbolxml\request\CorpCardExtOrgDataType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as commonDoc
     *
     * Общие реквизиты электронного документа
     *
     * @return \common\models\sbbolxml\request\CorpCardExtCommonDocType
     */
    public function getCommonDoc()
    {
        return $this->commonDoc;
    }

    /**
     * Sets a new commonDoc
     *
     * Общие реквизиты электронного документа
     *
     * @param \common\models\sbbolxml\request\CorpCardExtCommonDocType $commonDoc
     * @return static
     */
    public function setCommonDoc(\common\models\sbbolxml\request\CorpCardExtCommonDocType $commonDoc)
    {
        $this->commonDoc = $commonDoc;
        return $this;
    }

    /**
     * Gets as account
     *
     * Реквизиты бизнес счёта клиента
     *
     * @return \common\models\sbbolxml\request\CorpCardExtBusinessAccNumBicType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Реквизиты бизнес счёта клиента
     *
     * @param \common\models\sbbolxml\request\CorpCardExtBusinessAccNumBicType $account
     * @return static
     */
    public function setAccount(\common\models\sbbolxml\request\CorpCardExtBusinessAccNumBicType $account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Adds as personInfo
     *
     * Список карт, которые требуется выпустить
     *
     * @return static
     * @param \common\models\sbbolxml\request\CorpCardHolderPersonInfoType $personInfo
     */
    public function addToListCards(\common\models\sbbolxml\request\CorpCardHolderPersonInfoType $personInfo)
    {
        $this->listCards[] = $personInfo;
        return $this;
    }

    /**
     * isset listCards
     *
     * Список карт, которые требуется выпустить
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetListCards($index)
    {
        return isset($this->listCards[$index]);
    }

    /**
     * unset listCards
     *
     * Список карт, которые требуется выпустить
     *
     * @param scalar $index
     * @return void
     */
    public function unsetListCards($index)
    {
        unset($this->listCards[$index]);
    }

    /**
     * Gets as listCards
     *
     * Список карт, которые требуется выпустить
     *
     * @return \common\models\sbbolxml\request\CorpCardHolderPersonInfoType[]
     */
    public function getListCards()
    {
        return $this->listCards;
    }

    /**
     * Sets a new listCards
     *
     * Список карт, которые требуется выпустить
     *
     * @param \common\models\sbbolxml\request\CorpCardHolderPersonInfoType[] $listCards
     * @return static
     */
    public function setListCards(array $listCards)
    {
        $this->listCards = $listCards;
        return $this;
    }

    /**
     * Gets as cardTypeCode
     *
     * Код продукта КК
     *
     * @return string
     */
    public function getCardTypeCode()
    {
        return $this->cardTypeCode;
    }

    /**
     * Sets a new cardTypeCode
     *
     * Код продукта КК
     *
     * @param string $cardTypeCode
     * @return static
     */
    public function setCardTypeCode($cardTypeCode)
    {
        $this->cardTypeCode = $cardTypeCode;
        return $this;
    }


}

