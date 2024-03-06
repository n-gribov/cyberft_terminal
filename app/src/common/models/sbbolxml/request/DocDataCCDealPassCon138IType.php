<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataCCDealPassCon138IType
 *
 * Общие реквизиты паспорта сделки по контракту 138И
 * XSD Type: DocDataCCDealPassCon138I
 */
class DocDataCCDealPassCon138IType extends DocDataCCType
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

