<?php

namespace addons\VTB\jobs;

use addons\VTB\models\soap\messages\WSGetCustomer\GetAccountsListRequest;
use addons\VTB\models\soap\messages\WSGetCustomer\GetCustsAndBranchesRequest;
use addons\VTB\models\soap\services\WSGetCustomer;
use addons\VTB\models\VTBBankBranch;
use addons\VTB\models\VTBCustomer;
use addons\VTB\models\VTBCustomerAccount;
use common\models\vtbxml\service\Account;
use common\models\vtbxml\service\Accounts;
use common\models\vtbxml\service\Customer;
use common\models\vtbxml\service\BankBranch;
use common\models\vtbxml\service\Customers;
use Exception;

class UpdateVTBCustomersDataJob extends BaseJob
{
    public function perform()
    {
        $customers = $this->getCustomers();
        $customersAccounts = $this->getCustomersAccounts($customers);
        $this->fixEmptyBranchesBics($customers, $customersAccounts);

        $this->updateCustomers($customers);

        $branches = $this->getBranches($customers);
        $this->updateBranches($branches);

        foreach ($customers as $customer) {
            $accounts = $customersAccounts[$customer->id];
            $this->updateAccounts($customer->id, $accounts);
        }
    }

    /**
     * @param Customer[] $customers
     * @param array $customersAccounts
     */
    private function fixEmptyBranchesBics(array &$customers, array $customersAccounts)
    {
        $getBranchBicFromAccounts = function ($branchId, $customerId) use ($customersAccounts) {
            if (empty($branchId)) {
                return null;
            }

            /** @var Account[] $customerAccounts */
            $customerAccounts = $customersAccounts[$customerId];
            foreach ($customerAccounts as $account) {
                if ($branchId === $account->branchId) {
                    return $account->branchBic;
                }
            }

            return null;
        };

        foreach ($customers as $customer) {
            foreach ($customer->branches as $branch) {
                if (empty($branch->bic)) {
                    $branch->bic = $getBranchBicFromAccounts($branch->id, $customer->id);
                }
            }
        }
    }

    /**
     * @param Customer[] $customers
     * @return array
     */
    private function getCustomersAccounts(array $customers): array
    {
        return array_reduce(
            $customers,
            function (array $carry, Customer $customer): array {
                $carry[$customer->id] = $this->getCustomerAccounts($customer->id);
                return $carry;
            },
            []
        );
    }

    /**
     * @param Customer[] $customers
     */
    private function updateCustomers($customers)
    {
        $data = array_map(
            function (Customer $customer) {
                return [
                    'customerId'                     => $customer->id,
                    'clientId'                       => $customer->client,
                    'type'                           => $customer->type,
                    'propertyTypeId'                 => $customer->propertyType,
                    'name'                           => $customer->shortName,
                    'fullName'                       => $customer->fullName,
                    'inn'                            => $customer->inn,
                    'kpp'                            => $customer->kpp,
                    'okato'                          => $customer->okato,
                    'okpo'                           => $customer->okpo,
                    'countryCode'                    => $customer->lawCountry,
                    'addressState'                   => $customer->lawState,
                    'addressDistrict'                => $customer->lawDistrict,
                    'addressSettlement'              => $customer->lawCity,
                    'addressStreet'                  => $customer->lawStreet,
                    'addressBuilding'                => $customer->lawBuilding,
                    'addressBuildingBlock'           => $customer->lawBlock,
                    'addressApartment'               => $customer->lawOffice,
                    'addressZipCode'                 => $customer->lawZip,
                    'internationalName'              => $customer->internationalName,
                    'internationalAddressState'      => $customer->internationalState,
                    'internationalAddressSettlement' => $customer->internationalPlace,
                    'internationalStreetAddress'     => $customer->getInternationalStreetAddress(),
                    'internationalZipCode'           => $customer->internationalZip,
                ];
            },
            $customers
        );
        VTBCustomer::refreshAll($data);
    }

    /**
     * @param BankBranch[] $branches
     */
    private function updateBranches($branches)
    {
        $data = array_map(
            function (BankBranch $branch) {
                return [
                    'branchId'          => $branch->id,
                    'bik'               => $branch->bic,
                    'name'              => $branch->name,
                    'fullName'          => $branch->fullName,
                    'internationalName' => $branch->internationalName,
                ];
            },
            $branches
        );
        VTBBankBranch::refreshAll($data);
    }

    /**
     * @param $customerId
     * @param Account[] $accounts
     */
    private function updateAccounts($customerId, $accounts)
    {
        $data = array_map(
            function (Account $account) {
                return [
                    'customerId'     => $account->customerId,
                    'number'         => $account->number,
                    'bankBik'        => $account->branchBic,
                    'bankBranchId'   => $account->branchId,
                    'bankBranchName' => $account->branchName,
                ];
            },
            $accounts
        );
        VTBCustomerAccount::refreshAll($customerId, $data);
    }

    /**
     * @return Customer[]
     * @throws Exception
     */
    private function getCustomers()
    {
        $service = new WSGetCustomer();
        $request = new GetCustsAndBranchesRequest();
        $response = $service->getCustsAndBranches($request);
        if (!empty($response->getBSError())) {
            throw new Exception("Request WSGetCustomer/GetCustsAndBranches has failed, error: {$response->getBSErrorCode()}, {$response->getBSError()}");
        }

        return Customers::fromXml($response->getCustsAndBranches())->customers;
    }

    /**
     * @param Customer[] $customers
     * @return BankBranch[]
     */
    private function getBranches($customers)
    {
        $allBranches = array_reduce(
            $customers,
            function ($carry, Customer $customer) {
                return array_merge($carry, $customer->branches);
            },
            []
        );
        $branchesById = array_reduce(
            $allBranches,
            function ($carry, BankBranch $branch) {
                $carry[$branch->id] = $branch;
                return $carry;
            },
            []
        );
        return array_values($branchesById);
    }

    /**
     * @param integer $customerId
     * @return Account[]
     * @throws Exception
     */
    private function getCustomerAccounts($customerId)
    {
        $service = new WSGetCustomer();
        $request = (new GetAccountsListRequest())->setCustID($customerId);
        $response = $service->getAccountsList($request);
        if (!empty($response->getBSError())) {
            throw new Exception("Request WSGetCustomer/GetAccountsList has failed, error: {$response->getBSErrorCode()}, {$response->getBSError()}");
        }

        return Accounts::fromXml($response->getAccounts())->accounts;
    }
}
