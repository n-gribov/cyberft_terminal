<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RegOfIssCardsType
 *
 *
 * XSD Type: RegOfIssCards
 */
class RegOfIssCardsType extends DocBaseType
{

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\sbbolxml\request\RegOfIssCardsType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\sbbolxml\request\RegOfIssCardsType\AuthPersAType $authPers
     */
    private $authPers = null;

    /**
     * Реквизиты зарплатного договра
     *
     * @property \common\models\sbbolxml\request\RegOfIssCardsType\SalaryContractAType $salaryContract
     */
    private $salaryContract = null;

    /**
     * Адрес организации
     *  Можно заполнить автоматически
     *
     * @property \common\models\sbbolxml\request\AddressRegOfIssType $orgAddress
     */
    private $orgAddress = null;

    /**
     * Соглачие физ. лица получено
     *
     * @property boolean $accept
     */
    private $accept = null;

    /**
     * Итоговое количество сотрудниокв
     *
     * @property integer $total
     */
    private $total = null;

    /**
     * @property \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType[] $listOfIssCards
     */
    private $listOfIssCards = null;

    /**
     * Связнные документы. Их наличие может влиять на особенности обработки документа
     *  вАБС
     *
     * @property \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты документа
     *
     * @return \common\models\sbbolxml\request\RegOfIssCardsType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\RegOfIssCardsType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @return \common\models\sbbolxml\request\RegOfIssCardsType\AuthPersAType
     */
    public function getAuthPers()
    {
        return $this->authPers;
    }

    /**
     * Sets a new authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\AuthPersAType $authPers
     * @return static
     */
    public function setAuthPers(\common\models\sbbolxml\request\RegOfIssCardsType\AuthPersAType $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }

    /**
     * Gets as salaryContract
     *
     * Реквизиты зарплатного договра
     *
     * @return \common\models\sbbolxml\request\RegOfIssCardsType\SalaryContractAType
     */
    public function getSalaryContract()
    {
        return $this->salaryContract;
    }

    /**
     * Sets a new salaryContract
     *
     * Реквизиты зарплатного договра
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\SalaryContractAType $salaryContract
     * @return static
     */
    public function setSalaryContract(\common\models\sbbolxml\request\RegOfIssCardsType\SalaryContractAType $salaryContract)
    {
        $this->salaryContract = $salaryContract;
        return $this;
    }

    /**
     * Gets as orgAddress
     *
     * Адрес организации
     *  Можно заполнить автоматически
     *
     * @return \common\models\sbbolxml\request\AddressRegOfIssType
     */
    public function getOrgAddress()
    {
        return $this->orgAddress;
    }

    /**
     * Sets a new orgAddress
     *
     * Адрес организации
     *  Можно заполнить автоматически
     *
     * @param \common\models\sbbolxml\request\AddressRegOfIssType $orgAddress
     * @return static
     */
    public function setOrgAddress(\common\models\sbbolxml\request\AddressRegOfIssType $orgAddress)
    {
        $this->orgAddress = $orgAddress;
        return $this;
    }

    /**
     * Gets as accept
     *
     * Соглачие физ. лица получено
     *
     * @return boolean
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Sets a new accept
     *
     * Соглачие физ. лица получено
     *
     * @param boolean $accept
     * @return static
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;
        return $this;
    }

    /**
     * Gets as total
     *
     * Итоговое количество сотрудниокв
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Sets a new total
     *
     * Итоговое количество сотрудниокв
     *
     * @param integer $total
     * @return static
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Adds as issCardInfo
     *
     * @return static
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType $issCardInfo
     */
    public function addToListOfIssCards(\common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType $issCardInfo)
    {
        $this->listOfIssCards[] = $issCardInfo;
        return $this;
    }

    /**
     * isset listOfIssCards
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetListOfIssCards($index)
    {
        return isset($this->listOfIssCards[$index]);
    }

    /**
     * unset listOfIssCards
     *
     * @param scalar $index
     * @return void
     */
    public function unsetListOfIssCards($index)
    {
        unset($this->listOfIssCards[$index]);
    }

    /**
     * Gets as listOfIssCards
     *
     * @return \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType[]
     */
    public function getListOfIssCards()
    {
        return $this->listOfIssCards;
    }

    /**
     * Sets a new listOfIssCards
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType[] $listOfIssCards
     * @return static
     */
    public function setListOfIssCards(array $listOfIssCards)
    {
        $this->listOfIssCards = $listOfIssCards;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Связнные документы. Их наличие может влиять на особенности обработки документа
     *  вАБС
     *
     * @return static
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Связнные документы. Их наличие может влиять на особенности обработки документа
     *  вАБС
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetLinkedDocs($index)
    {
        return isset($this->linkedDocs[$index]);
    }

    /**
     * unset linkedDocs
     *
     * Связнные документы. Их наличие может влиять на особенности обработки документа
     *  вАБС
     *
     * @param scalar $index
     * @return void
     */
    public function unsetLinkedDocs($index)
    {
        unset($this->linkedDocs[$index]);
    }

    /**
     * Gets as linkedDocs
     *
     * Связнные документы. Их наличие может влиять на особенности обработки документа
     *  вАБС
     *
     * @return \common\models\sbbolxml\request\LinkedDocsType\LDocAType[]
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Связнные документы. Их наличие может влиять на особенности обработки документа
     *  вАБС
     *
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}

