<?php

use common\settings\AppSettings;
use yii\web\View;

/** @var array $data */
/** @var View $this */

/** @var AppSettings $settings */
$settings = $data['globalSettings'];

echo $this->render(
    '@common/modules/autobot/views/shared/_apiIntegration',
    [
        'settings' => $settings,
        'enableCheckboxLabel' => Yii::t('app', 'Enable importing and exporting documents via API for all terminals'),
    ]
);
