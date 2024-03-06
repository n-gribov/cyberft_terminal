<?php

namespace addons\VTB\models\soap\services;

use addons\VTB\models\soap\messages\WSGetCustomer\GetAccountsListRequest;
use addons\VTB\models\soap\messages\WSGetCustomer\GetAccountsListResponse;
use addons\VTB\models\soap\messages\WSGetCustomer\GetCustsAndBranchesRequest;
use addons\VTB\models\soap\messages\WSGetCustomer\GetCustsAndBranchesResponse;

class WSGetCustomer extends BaseService
{
    /**
     * @param GetAccountsListRequest $request
     * @return GetAccountsListResponse
     */
    public function getAccountsList(GetAccountsListRequest $request)
    {
        return $this->execute('GetAccountsList', $request);
    }

    /**
     * @param GetCustsAndBranchesRequest $request
     * @return GetCustsAndBranchesResponse
     */
    public function getCustsAndBranches(GetCustsAndBranchesRequest $request)
    {
        return $this->execute('GetCustsAndBranches', $request);
    }

}
