<?php
namespace addons\edm\settings;

use common\settings\BaseSettings;
use yii\helpers\ArrayHelper;
use Yii;

class EdmSettings extends BaseSettings
{
    public $signaturesNumber = 1;
    public $useAutosigning = false;

    public $bankName = 'ООО КБ «ПЛАТИНА»';
    public $exportXml = false;

    public function attributeLabels()
    {
        return [
            'bankName' => Yii::t('edm', 'Bank name'),
            'exportXml' => Yii::t('app', 'Activate XML export'),
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            ['exportXml', 'boolean'],
        ]);
    }
}
