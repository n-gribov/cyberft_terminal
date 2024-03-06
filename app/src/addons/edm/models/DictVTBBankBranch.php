<?php

namespace addons\edm\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class DictVTBBankBranch
 * @package addons\edm\models
 * @property integer $id
 * @property integer $branchId
 * @property string  $bik
 * @property string  $name
 * @property string  $fullName
 * @property string  $internationalName
 */
class DictVTBBankBranch extends ActiveRecord
{
    public function rules()
    {
        return [
            [['branchId', 'bik', 'name', 'fullName', 'internationalName'], 'safe'],
        ];
    }

    public static function tableName()
    {
        return 'edmDictVTBBankBranch';
    }

    public static function refreshAll($data)
    {
        $newBranchesIds = ArrayHelper::getColumn($data, 'branchId');
        static::deleteAll(['not in', 'branchId', $newBranchesIds]);

        foreach ($data as $attributes) {
            $branch = self::findOneByBranchId($attributes['branchId']);
            if ($branch === null) {
                $branch = new DictVTBBankBranch();
            }
            $branch->setAttributes($attributes);
            $branch->save();
        }
    }

    public static function findOneByBranchId($branchId)
    {
        return static::findOne(['branchId' => $branchId]);
    }
}
