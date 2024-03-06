<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CurrBuyType
 *
 *
 * XSD Type: CurrBuy
 */
class CurrBuyType extends DocBaseType
{

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\sbbolxml\request\CurrComDocDataType $docData
     */
    private $docData = null;

    /**
     * Сделка
     *
     * @property \common\models\sbbolxml\request\CurrBuyTransType $trans
     */
    private $trans = null;

    /**
     * Соглашение с банком
     *
     * @property string $bankAgreement
     */
    private $bankAgreement = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Связанные документы, например, платежное поручение или валютный перевод
     *
     * @property \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\sbbolxml\request\CurrComDocDataType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @param \common\models\sbbolxml\request\CurrComDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\CurrComDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as trans
     *
     * Сделка
     *
     * @return \common\models\sbbolxml\request\CurrBuyTransType
     */
    public function getTrans()
    {
        return $this->trans;
    }

    /**
     * Sets a new trans
     *
     * Сделка
     *
     * @param \common\models\sbbolxml\request\CurrBuyTransType $trans
     * @return static
     */
    public function setTrans(\common\models\sbbolxml\request\CurrBuyTransType $trans)
    {
        $this->trans = $trans;
        return $this;
    }

    /**
     * Gets as bankAgreement
     *
     * Соглашение с банком
     *
     * @return string
     */
    public function getBankAgreement()
    {
        return $this->bankAgreement;
    }

    /**
     * Sets a new bankAgreement
     *
     * Соглашение с банком
     *
     * @param string $bankAgreement
     * @return static
     */
    public function setBankAgreement($bankAgreement)
    {
        $this->bankAgreement = $bankAgreement;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Дополнительная информация
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
     * Дополнительная информация
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Связанные документы, например, платежное поручение или валютный перевод
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
     * Связанные документы, например, платежное поручение или валютный перевод
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
     * Связанные документы, например, платежное поручение или валютный перевод
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
     * Связанные документы, например, платежное поручение или валютный перевод
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
     * Связанные документы, например, платежное поручение или валютный перевод
     *
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }


}

