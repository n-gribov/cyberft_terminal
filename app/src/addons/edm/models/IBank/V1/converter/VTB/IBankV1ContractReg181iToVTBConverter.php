<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBContractReg\VTBContractRegType;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use common\base\BaseType;
use common\helpers\vtb\VTBHelper;
use common\models\vtbxml\documents\ContractReg;

class IBankV1ContractReg181iToVTBConverter extends BaseIBankV1ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBContractRegType::class;
    const EXT_MODEL_CLASS = VTBContractRequestExt::class;
    const VTB_DOCUMENT_VERSION = 1;
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
        'DPNUMBEROTHERBANK',
        ['NONRESIDENTINFO' => ['NAME', 'COUNTRYCODE']],
        'DATEOGRN',
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var ContractReg $document */
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
