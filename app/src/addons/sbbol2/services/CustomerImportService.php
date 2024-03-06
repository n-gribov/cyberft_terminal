<?php

namespace addons\sbbol2\services;

use addons\sbbol2\models\Sbbol2Customer;
use addons\sbbol2\models\Sbbol2CustomerAccessToken;
use addons\sbbol2\models\Sbbol2CustomerAccount;
use common\models\sbbol2\Account;
use common\models\sbbol2\Address;
use common\models\sbbol2\ClientInfo;
use Exception;
use Throwable;
use Yii;
use yii\authclient\OAuthToken;

class CustomerImportService
{
    public function createOrUpdate(ClientInfo $clientInfo, string $terminalAddress = null, OAuthToken $authToken = null): Sbbol2Customer
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $customer = $this->saveCustomer($clientInfo, $terminalAddress);
            $this->saveAccounts($customer->id, $clientInfo->getAccounts());
            if ($authToken !== null) {
                $this->saveAccessToken($customer->id, $authToken);
            }
            $transaction->commit();
            return $customer;
        } catch (Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    private function saveCustomer(ClientInfo $clientInfo, string $terminalAddress = null): Sbbol2Customer
    {
        if (empty($clientInfo->getInn())) {
            throw new Exception('Sberbank client info has empty INN');
        }

        $customer = Sbbol2Customer::findOne(['inn' => $clientInfo->getInn()]);
        if ($customer === null) {
            $customer = new Sbbol2Customer();
        }
        $address = $this->extractClientAddress($clientInfo);

        $customer->setAttributes(
            [
                'inn'                   => $clientInfo->getInn(),
                'fullName'              => $clientInfo->getFullName(),
                'shortName'             => $clientInfo->getShortName(),
                'kpp'                   => $clientInfo->getKpps()[0] ?? null,
                'ogrn'                  => $clientInfo->getOgrn(),
                'okato'                 => $clientInfo->getOkato(),
                'okpo'                  => $clientInfo->getOkpo(),
                'orgForm'               => $clientInfo->getOrgForm(),
                'addressArea'           => $address ? $address->getArea() : null,
                'addressBuilding'       => $address ? $address->getBuilding() : null,
                'addressCity'           => $address ? $address->getCity() : null,
                'addressCountryCode'    => $address ? $address->getCountry() : null,
                'addressFlat'           => $address ? $address->getFlat() : null,
                'addressHouse'          => $address ? $address->getHouse() : null,
                'addressRegion'         => $address ? $address->getRegion() : null,
                'addressSettlement'     => $address ? $address->getSettlement() : null,
                'addressSettlementType' => $address ? $address->getSettlementType() : null,
                'addressStreet'         => $address ? $address->getStreet() : null,
                'addressZip'            => $address ? $address->getZip() : null,
                'terminalAddress'       => $terminalAddress ?: $customer->terminalAddress,
            ],
            false
        );
        // Сохранить модель в БД
        $isSaved = $customer->save();
        if (!$isSaved) {
            throw new Exception('Failed to save customer, errors: ' . var_export($customer->getErrors(), true));
        }

        return $customer;
    }

    /**
     * @param ClientInfo $clientInfo
     * @return Address|null
     */
    private function extractClientAddress(ClientInfo $clientInfo)
    {
        $addresses = $clientInfo->getAddresses() ?: [];
        foreach ($addresses as $address) {
            if ($address->getType() === 'Юридический') {
                return $address;
            }
        }
        return $addresses[0] ?? null;
    }

    /**
     * @param int $customerId
     * @param Account[] $accounts
     */
    private function saveAccounts(int $customerId, array $accounts)
    {
        $accountsAttributes = array_map(
            function (Account $account) {
                return [
                    'number'       => $account->getNumber(),
                    'bic'          => $account->getBic(),
                    'currencyCode' => $account->getCurrencyCode(),
                ];
            },
            $accounts
        );
        $isSaved = Sbbol2CustomerAccount::refreshAll($customerId, $accountsAttributes);
        if (!$isSaved) {
            throw new Exception('Failed to save customer accounts');
        }
    }

    private function saveAccessToken(int $customerId, OAuthToken $authToken)
    {
        $customerToken = Sbbol2CustomerAccessToken::findOne(['customerId' => $customerId]);
        if ($customerToken === null) {
            $customerToken = new Sbbol2CustomerAccessToken(['customerId' => $customerId]);
        }
        $expiryTime = $authToken->getExpireDuration()
            ? date('Y-m-d H:i:s', time() + $authToken->getExpireDuration())
            : null;
        $customerToken->setAttributes(
            [
                'isActive' => true,
                'accessToken' => $authToken->getToken(),
                'refreshToken' => $authToken->getParam('refresh_token'),
                'accessTokenExpiryTime' => $expiryTime,
            ],
            false
        );
        // Сохранить модель в БД
        $isSaved = $customerToken->save();
        if (!$isSaved) {
            throw new Exception('Failed to save customer access token');
        }
    }
}
