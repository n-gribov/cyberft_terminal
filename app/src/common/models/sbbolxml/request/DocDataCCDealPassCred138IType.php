<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataCCDealPassCred138IType
 *
 * Общие реквизиты паспорта сделки по кредиту 138И
 * XSD Type: DocDataCCDealPassCred138I
 */
class DocDataCCDealPassCred138IType extends DocDataCCType
{

    /**
     * Признак оформления ПС в случае уступки прав или перевода долга по контракту:
     *  0 - признак не установлен
     *  1 - признак установлен
     *
     * @property boolean $transferDebtRights
     */
    private $transferDebtRights = null;

    /**
     * Gets as transferDebtRights
     *
     * Признак оформления ПС в случае уступки прав или перевода долга по контракту:
     *  0 - признак не установлен
     *  1 - признак установлен
     *
     * @return boolean
     */
    public function getTransferDebtRights()
    {
        return $this->transferDebtRights;
    }

    /**
     * Sets a new transferDebtRights
     *
     * Признак оформления ПС в случае уступки прав или перевода долга по контракту:
     *  0 - признак не установлен
     *  1 - признак установлен
     *
     * @param boolean $transferDebtRights
     * @return static
     */
    public function setTransferDebtRights($transferDebtRights)
    {
        $this->transferDebtRights = $transferDebtRights;
        return $this;
    }


}

