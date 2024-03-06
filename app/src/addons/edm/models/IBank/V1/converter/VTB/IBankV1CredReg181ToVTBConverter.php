<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use addons\edm\models\VTBCredReg\VTBCredRegType;
use common\base\BaseType;
use common\helpers\vtb\VTBHelper;
use common\models\vtbxml\documents\CredReg;

class IBankV1CredReg181ToVTBConverter extends BaseIBankV1ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBCredRegType::class;
    const EXT_MODEL_CLASS = VTBContractRequestExt::class;
    const VTB_DOCUMENT_VERSION = 5;
    const MAPPING = [
        null,
        'CUSTID',
        'DOCUMENTDATE',
        'DOCUMENTNUMBER',
        'SENDEROFFICIALS',
        'PHONEOFFICIALS',
        'DPNUM1',
        'DPNUM4',
        'DPNUM5',
        'DPDATE',
        'CONNUMBER',
        'ISCONNUMBER',
        'CONDATE',
        'CONCURRCODE',
        'CONAMOUNT',
        'ISCONAMOUNT',
        'CONENDDATE',
        'CONAMOUNTTRANSFER',
        'DPNUMBEROTHERBANK',
        'CREDAMOUNTCURR',
        'CREDPAYPERIODCODE',
        'FIXRATEPERCENT',
        'LIBORRATE',
        'OTHERRATEMETHOD',
        'INCREASERATEPERCENT',
        'DEBTSAMOUNT',
        'ISDIRECTINVESTING',
        'DEPOSITAMOUNT',
        'OTHERPAYMENTS',
        'FLAGPAYMENTRETURN',
        [
            'NONRESIDENTINFO' => [
                'NAME',
                'COUNTRYCODE',
            ]
        ],
        [
            'CREDTRANCHEBLOB' => [
                'TRANCHEAMOUNT',
                'TRANCHEPAYMENTPERIODCODE',
                'RECEIPTDATE',
            ]
        ],
        [
            'PAYMENTRETURNBLOB' => [
                'PAYMENTDEBTDATE',
                'PAYMENTDEBTAMOUNT',
                'PAYMENTPERCENTDATE',
                'PAYMENTPERCENTAMOUNT',
                'SPECIALCONDITIONS',
            ]
        ],
        [
            'CREDRECEIPTINFOBLOB' => [
                'BENEFICIAR',
                'BENEFICIARCOUNTRYCODE',
                'ISCREDAMOUNT',
                'CREDAMOUNT',
                'CREDPERCENT',
            ]
        ],
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var CredReg $document */
        $document = $typeModel->document;

        $organization = VTBHelper::getOrganizationByVTBCustomerId($document->CUSTID);
        if ($organization !== null) {
            $document->CUSTOMERNAME = $organization->name;
            $document->CUSTOMERINN = $organization->inn;
            $document->CUSTOMERKPP = $organization->kpp;
            $document->CUSTOMEROGRN = $organization->ogrn;

            $dateOgrn = $organization->dateEgrul ? \DateTime::createFromFormat('d.m.Y', $organization->dateEgrul) : null;
            if ($dateOgrn) {
                $document->DATEOGRN = $dateOgrn;
            }
            $document->LAWSTATE = $organization->state;
            $document->LAWCITY = $organization->city;
            $document->LAWSTREET = $organization->street;
            $document->LAWBLOCK = $organization->building;
            $document->LAWBUILDING = $organization->buildingNumber;
            $document->LAWDISTRICT = $organization->district;
            $document->LAWPLACE = $organization->city;
        }

        $this->fillCustomerBankAttributes($typeModel, $ibankDocument);
    }

    public function createExtModelAttributes(BaseType $typeModel): array
    {
        $extModel = new VTBContractRequestExt();
        $extModel->loadContentModel($typeModel);
        return array_merge(
            $extModel->dirtyAttributes,
            ['contractsAttributes' => $extModel->contractsAttributes]
        );
    }
}
