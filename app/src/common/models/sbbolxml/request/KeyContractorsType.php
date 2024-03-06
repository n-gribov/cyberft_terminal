<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing KeyContractorsType
 *
 * Информация об основных контрагентах, планируемых плательщиках и получателях по операциям с денежными средствами,
 *  находящимися на счете
 * XSD Type: KeyContractors
 */
class KeyContractorsType
{

    /**
     * Плательщики (наименование, ФИО, ИНН/КИО)
     *
     * @property string $keyContractorPayer
     */
    private $keyContractorPayer = null;

    /**
     * Получатели (наименование, ФИО, ИНН/КИО)
     *
     * @property string $keyContractorPayee
     */
    private $keyContractorPayee = null;

    /**
     * Gets as keyContractorPayer
     *
     * Плательщики (наименование, ФИО, ИНН/КИО)
     *
     * @return string
     */
    public function getKeyContractorPayer()
    {
        return $this->keyContractorPayer;
    }

    /**
     * Sets a new keyContractorPayer
     *
     * Плательщики (наименование, ФИО, ИНН/КИО)
     *
     * @param string $keyContractorPayer
     * @return static
     */
    public function setKeyContractorPayer($keyContractorPayer)
    {
        $this->keyContractorPayer = $keyContractorPayer;
        return $this;
    }

    /**
     * Gets as keyContractorPayee
     *
     * Получатели (наименование, ФИО, ИНН/КИО)
     *
     * @return string
     */
    public function getKeyContractorPayee()
    {
        return $this->keyContractorPayee;
    }

    /**
     * Sets a new keyContractorPayee
     *
     * Получатели (наименование, ФИО, ИНН/КИО)
     *
     * @param string $keyContractorPayee
     * @return static
     */
    public function setKeyContractorPayee($keyContractorPayee)
    {
        $this->keyContractorPayee = $keyContractorPayee;
        return $this;
    }


}

