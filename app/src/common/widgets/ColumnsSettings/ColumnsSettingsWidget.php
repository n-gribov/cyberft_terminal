<?php

namespace common\widgets\ColumnsSettings;

use Yii;
use yii\base\Widget;
use common\models\UserColumnsSettings;

class ColumnsSettingsWidget extends Widget
{
    public $listType;
    public $model;
    public $columns;
    public $columnsDisabledByDefault = [];

    public function run()
    {
        // Список всех колонок для данного журнала
        $settingsColumns = UserColumnsSettings::getColumnsForForm(
            $this->columns,
            $this->listType,
            Yii::$app->user->id,
            $this->columnsDisabledByDefault
        );

        // Вывести страницу
        return $this->render('view', [
            'settingsColumns' => $settingsColumns,
            'model' => $this->model,
            'listType' => $this->listType
        ]);
    }
}
