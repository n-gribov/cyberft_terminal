<?php

namespace common\modules\autobot\models;

use common\models\Processing;
use common\settings\AppSettings;
use Yii;
use yii\base\Model;

class ProcessingSettingsForm extends Model
{
    public $safeMode;
    public $processingId;

    public function init()
    {
        parent::init();

        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        $this->safeMode = $appSettings->processing['safeMode'];
        $processing = Processing::findOne([
            'dsn' => $appSettings->processing['dsn'],
            'address' => $appSettings->processing['address'],
        ]);
        if ($processing) {
            $this->processingId = $processing->id;
        } else {
            $defaultProcessing = Processing::findOne(['isDefault' => true]);
            if ($defaultProcessing) {
                $this->processingId = $defaultProcessing->id;
            }
        }
    }

    public function rules()
    {
        return [
            ['safeMode', 'boolean'],
            ['processingId', 'integer'],
            [['safeMode', 'processingId'], 'safe'],
        ];
    }

    public function save()
    {
        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        $appSettings->processing['safeMode'] = (bool)$this->safeMode;
        $processing = Processing::findOne($this->processingId);
        if (!$processing) {
            return false;
        }
        $appSettings->processing['dsn'] = $processing->dsn;
        $appSettings->processing['address'] = $processing->address;

        // Сохранить модель в БД и вернуть результат сохранения
        return $appSettings->save();
    }
}
