<?php

namespace addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;

use common\helpers\Countries;
use common\models\listitem\NestedListItem;
use Yii;

/**
 * @property string|null $countryName
 */
class NonResident extends NestedListItem
{
    public $id;
    public $name;
    public $countryCode;

    public function rules()
    {
        return [
            ['id', 'integer'],
            [['name', 'countryCode'], 'string'],
            [['name', 'countryCode'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('edm', 'Non-resident name'),
            'countryCode' => Yii::t('edm', 'Country (code)'),
            'countryName' => Yii::t('edm', 'Country (name)'),
        ];
    }

    public function getCountryName()
    {
        return $this->countryCode ? Countries::getNameByNumericCode($this->countryCode) : null;
    }
}
