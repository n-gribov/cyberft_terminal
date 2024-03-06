<?php

namespace addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm;

use addons\edm\models\DictVTBContractUnregistrationGround;
use common\models\listitem\NestedListItem;
use Yii;

/**
 * @property string|null $unregistrationGroundName
 */
class Contract extends NestedListItem
{
    public $id;
    public $uniqueContractNumber;
    public $uniqueContractNumberDate;
    public $unregistrationGroundCode;

    public function rules()
    {
        return [
            ['id', 'integer'],
            [['uniqueContractNumber', 'unregistrationGroundCode'], 'string'],
            ['uniqueContractNumberDate', 'date', 'format' => 'dd.MM.yyyy'],
            [['uniqueContractNumber', 'unregistrationGroundCode', 'uniqueContractNumberDate'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'uniqueContractNumber' => Yii::t('edm', 'Unique contract number'),
            'uniqueContractNumberDate' => Yii::t('edm', 'Unique contract number date'),
            'unregistrationGroundCode' => Yii::t('edm', 'Unregistration ground'),
            'unregistrationGroundName' => Yii::t('edm', 'Instruction item'),
        ];
    }

    public function getUnregistrationGroundName()
    {
        $ground = DictVTBContractUnregistrationGround::findOneByCode($this->unregistrationGroundCode);
        return $ground ? $ground->name : null;
    }
}
