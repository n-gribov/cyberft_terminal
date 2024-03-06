<?php

namespace addons\SBBOL\helpers;

use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLCustomerAccount;
use addons\SBBOL\models\SBBOLCustomerKeyOwner;
use addons\SBBOL\models\SBBOLOrganization;
use common\models\sbbolxml\response\AccountRubType;
use common\models\sbbolxml\response\OrganizationInfoType;
use common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AddressesAType\AddressAType;
use common\models\sbbolxml\response\OrganizationInfoType\SignDevicesAType\SignDeviceAType;

class SBBOLCustomerHelper
{
    public static function saveHoldingHeadCustomer(OrganizationInfoType $organizationInfo, $senderName = null, $login = null, $password = null)
    {
        return static::saveCustomer(
            $organizationInfo,
            true,
            $organizationInfo->getOrgData()->getOrgId(),
            $senderName,
            $login,
            $password
        );
    }

    public static function saveCustomer(OrganizationInfoType $organizationInfo, $isHoldingHead, $holdingHeadId, $senderName, $login = null, $password = null)
    {
        $orgData = $organizationInfo->getOrgData();

        $organizationIsCreated = static::createOrUpdateOrganization($orgData->getINN(), $orgData->getFullName());
        if (!$organizationIsCreated) {
            return false;
        }

        $customer = SBBOLCustomer::findOne(['id' => $orgData->getOrgId()]);
        if ($customer === null) {
            $customer = new SBBOLCustomer();
        }

        /** @var AddressAType|null $address */
        $address = array_reduce(
            $orgData->getAddresses() ?: [],
            function ($carry, AddressAType $address) {
                return $address->getAddressTypeCode() === 'juridical' ? $address : $carry;
            }
        );

        $lastCertificateNumber = $customer->isNewRecord
            ? hexdec(@$orgData->getOtherOrgData()->getLastCertifNum())
            : $customer->lastCertNumber;
        $customer->setAttributes([
            'id'                   => $orgData->getOrgId(),
            'shortName'            => $orgData->getShortName(),
            'fullName'             => $orgData->getFullName(),
            'internationalName'    => @$orgData->getOtherOrgData()->getInternationalName(),
            'propertyType'         => $orgData->getOrgForm(),
            'inn'                  => $orgData->getINN(),
            'kpp'                  => @$orgData->getOtherOrgData()->getOrgKpp()[0]->getKPPIndex(),
            'ogrn'                 => $orgData->getOGRN(),
            'dateOgrn'             => $orgData->getDateOGRN() ? $orgData->getDateOGRN()->format('d.m.Y') : null,
            'countryCode'          => @$address->getCountry(),
            'addressState'         => @$address->getSub(),
            'addressDistrict'      => @$address->getArea(),
            'addressSettlement'    => @$address->getSettlName(),
            'addressStreet'        => @$address->getStr(),
            'addressBuilding'      => @$address->getHNumber(),
            'addressBuildingBlock' => @$address->getCorp(),
            'addressApartment'     => @$address->getOffice(),
            'isHoldingHead'        => $isHoldingHead,
            'certAuthId'           => @$orgData->getOtherOrgData()->getCertAuthId(),
            'lastCertNumber'       => $lastCertificateNumber,
            'bankBranchSystemName' => @$organizationInfo->getBranches()[0]->getSystemName(),
        ]);

        $optionalAttributes = [
            'holdingHeadId' => $holdingHeadId,
            'login' => $login,
            'password' => $password,
            'senderName' => $senderName
        ];
        foreach ($optionalAttributes as $attribute => $value) {
            if ($value !== null) {
                $customer->setAttribute($attribute, $value);
            }
        }

        // Сохранить модель в БД
        $isSaved = $customer->save();
        if (!$isSaved) {
            \Yii::info('Failed to save customer, errors: ' . var_export($customer->getErrors(), true));

            return false;
        }

        return static::saveCustomerAccounts($customer, $orgData->getAccounts()) && static::saveCustomerKeyOwners($customer, $organizationInfo);
    }

    private static function saveCustomerAccounts(SBBOLCustomer $customer, array $accounts): bool
    {
        $accountsAttributes = array_map(
            function (AccountRubType $orgAccount) {
                return [
                    'id'           => $orgAccount->getAccountId(),
                    'number'       => $orgAccount->getAccNum(),
                    'bankBik'      => @$orgAccount->getBank()->getBic(),
                    'currencyCode' => @$orgAccount->getOtherAccountData()->getCurrencyCode(),
                ];
            },
            $accounts
        );

        return SBBOLCustomerAccount::refreshAll($customer->id, $accountsAttributes);
    }

    private static function saveCustomerKeyOwners(SBBOLCustomer $customer, OrganizationInfoType $organizationInfo): bool
    {
        $infocryptSignDevices = array_filter(
            $organizationInfo->getSignDevices(),
            function (SignDeviceAType $signDevice) {
                return $signDevice->getCryptoTypeName() === 'Инфокрипт';
            }
        );

        $keyOwnersAttributes = [];
        foreach ($organizationInfo->getAuthPersons() as $authPerson) {
            if ($authPerson->getBlocked()) {
                continue;
            }
            $authPersonSignDevicesId = array_map(
                function (\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType $signDevice) {
                    return $signDevice->getSignDeviceId();
                },
                $authPerson->getSignDevices()
            );

            /** @var SignDeviceAType[] $authPersonInfocryptSignDevices */
            $authPersonInfocryptSignDevices = array_values(
                array_filter(
                    $infocryptSignDevices,
                    function (SignDeviceAType $signDevice) use ($authPersonSignDevicesId) {
                        return in_array($signDevice->getSignDeviceId(), $authPersonSignDevicesId);
                    }
                )
            );

            if (empty($authPersonInfocryptSignDevices)) {
                continue;
            }

            $keyOwnersAttributes[] = [
                'id'           => $authPerson->getUserGuid(),
                'fullName'     => $authPerson->getFIO(),
                'position'     => $authPersonInfocryptSignDevices[0]->getPost(),
                'signDeviceId' => $authPersonInfocryptSignDevices[0]->getSignDeviceId(),
            ];
        }

        return SBBOLCustomerKeyOwner::refreshAll($customer->id, $keyOwnersAttributes);
    }

    private static function createOrUpdateOrganization($inn, $fullName): bool
    {
        $organization = SBBOLOrganization::findOne($inn);
        if ($organization === null) {
            $organization = new SBBOLOrganization();
        }

        $organization->setAttributes([
            'inn' => $inn,
            'fullName' => $fullName,
        ]);
        // Сохранить модель в БД
        $isSaved = $organization->save();
        if (!$isSaved) {
            \Yii::info('Failed to save organization, errors: ' . var_export($organization->getErrors(), true));
        }

        return $isSaved;
    }
}
