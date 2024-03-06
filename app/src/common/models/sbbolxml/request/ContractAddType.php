<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ContractAddType
 *
 * Добавление контракта ГОЗ
 * XSD Type: ContractAdd
 */
class ContractAddType
{

    /**
     * Идентификатор документа в СББОЛ. При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
     *
     * @property string $essenceId
     */
    private $essenceId = null;

    /**
     * @property string $idGC
     */
    private $idGC = null;

    /**
     * Клиент является: 1 - Заказчиком, 2 - Исполнителем
     *
     * @property boolean $isCustExec
     */
    private $isCustExec = null;

    /**
     * @property \common\models\sbbolxml\request\ContractAddType\GOZContractAType $gOZContract
     */
    private $gOZContract = null;

    /**
     * @property \common\models\sbbolxml\request\ContractAddType\ContractAmountAType $contractAmount
     */
    private $contractAmount = null;

    /**
     * Условия поставки-оплаты
     *
     * @property string $termDeliverPay
     */
    private $termDeliverPay = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * @property \common\models\sbbolxml\request\GozCustomerType $customer
     */
    private $customer = null;

    /**
     * @property \common\models\sbbolxml\request\GozExecutorType $executor
     */
    private $executor = null;

    /**
     * Ссылки на вложенные файлы
     *
     * @property \common\models\sbbolxml\request\BFAttachmentType[] $bFAttachments
     */
    private $bFAttachments = null;

    /**
     * Gets as essenceId
     *
     * Идентификатор документа в СББОЛ. При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
     *
     * @return string
     */
    public function getEssenceId()
    {
        return $this->essenceId;
    }

    /**
     * Sets a new essenceId
     *
     * Идентификатор документа в СББОЛ. При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей записи с данным id
     *
     * @param string $essenceId
     * @return static
     */
    public function setEssenceId($essenceId)
    {
        $this->essenceId = $essenceId;
        return $this;
    }

    /**
     * Gets as idGC
     *
     * @return string
     */
    public function getIdGC()
    {
        return $this->idGC;
    }

    /**
     * Sets a new idGC
     *
     * @param string $idGC
     * @return static
     */
    public function setIdGC($idGC)
    {
        $this->idGC = $idGC;
        return $this;
    }

    /**
     * Gets as isCustExec
     *
     * Клиент является: 1 - Заказчиком, 2 - Исполнителем
     *
     * @return boolean
     */
    public function getIsCustExec()
    {
        return $this->isCustExec;
    }

    /**
     * Sets a new isCustExec
     *
     * Клиент является: 1 - Заказчиком, 2 - Исполнителем
     *
     * @param boolean $isCustExec
     * @return static
     */
    public function setIsCustExec($isCustExec)
    {
        $this->isCustExec = $isCustExec;
        return $this;
    }

    /**
     * Gets as gOZContract
     *
     * @return \common\models\sbbolxml\request\ContractAddType\GOZContractAType
     */
    public function getGOZContract()
    {
        return $this->gOZContract;
    }

    /**
     * Sets a new gOZContract
     *
     * @param \common\models\sbbolxml\request\ContractAddType\GOZContractAType $gOZContract
     * @return static
     */
    public function setGOZContract(\common\models\sbbolxml\request\ContractAddType\GOZContractAType $gOZContract)
    {
        $this->gOZContract = $gOZContract;
        return $this;
    }

    /**
     * Gets as contractAmount
     *
     * @return \common\models\sbbolxml\request\ContractAddType\ContractAmountAType
     */
    public function getContractAmount()
    {
        return $this->contractAmount;
    }

    /**
     * Sets a new contractAmount
     *
     * @param \common\models\sbbolxml\request\ContractAddType\ContractAmountAType $contractAmount
     * @return static
     */
    public function setContractAmount(\common\models\sbbolxml\request\ContractAddType\ContractAmountAType $contractAmount)
    {
        $this->contractAmount = $contractAmount;
        return $this;
    }

    /**
     * Gets as termDeliverPay
     *
     * Условия поставки-оплаты
     *
     * @return string
     */
    public function getTermDeliverPay()
    {
        return $this->termDeliverPay;
    }

    /**
     * Sets a new termDeliverPay
     *
     * Условия поставки-оплаты
     *
     * @param string $termDeliverPay
     * @return static
     */
    public function setTermDeliverPay($termDeliverPay)
    {
        $this->termDeliverPay = $termDeliverPay;
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
     * Gets as customer
     *
     * @return \common\models\sbbolxml\request\GozCustomerType
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Sets a new customer
     *
     * @param \common\models\sbbolxml\request\GozCustomerType $customer
     * @return static
     */
    public function setCustomer(\common\models\sbbolxml\request\GozCustomerType $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Gets as executor
     *
     * @return \common\models\sbbolxml\request\GozExecutorType
     */
    public function getExecutor()
    {
        return $this->executor;
    }

    /**
     * Sets a new executor
     *
     * @param \common\models\sbbolxml\request\GozExecutorType $executor
     * @return static
     */
    public function setExecutor(\common\models\sbbolxml\request\GozExecutorType $executor)
    {
        $this->executor = $executor;
        return $this;
    }

    /**
     * Adds as bFAttachment
     *
     * Ссылки на вложенные файлы
     *
     * @return static
     * @param \common\models\sbbolxml\request\BFAttachmentType $bFAttachment
     */
    public function addToBFAttachments(\common\models\sbbolxml\request\BFAttachmentType $bFAttachment)
    {
        $this->bFAttachments[] = $bFAttachment;
        return $this;
    }

    /**
     * isset bFAttachments
     *
     * Ссылки на вложенные файлы
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBFAttachments($index)
    {
        return isset($this->bFAttachments[$index]);
    }

    /**
     * unset bFAttachments
     *
     * Ссылки на вложенные файлы
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBFAttachments($index)
    {
        unset($this->bFAttachments[$index]);
    }

    /**
     * Gets as bFAttachments
     *
     * Ссылки на вложенные файлы
     *
     * @return \common\models\sbbolxml\request\BFAttachmentType[]
     */
    public function getBFAttachments()
    {
        return $this->bFAttachments;
    }

    /**
     * Sets a new bFAttachments
     *
     * Ссылки на вложенные файлы
     *
     * @param \common\models\sbbolxml\request\BFAttachmentType[] $bFAttachments
     * @return static
     */
    public function setBFAttachments(array $bFAttachments)
    {
        $this->bFAttachments = $bFAttachments;
        return $this;
    }


}

