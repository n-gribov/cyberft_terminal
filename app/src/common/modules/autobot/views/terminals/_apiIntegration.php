<?php

use common\settings\AppSettings;
use yii\web\View;

/** @var array $data */
/** @var View $this */

/** @var AppSettings $terminalSettings */
$terminalSettings = $data['terminalSettings'];

/** @var AppSettings $globalSettings */
$globalSettings = $data['globalSettings'];

$enableCheckboxLabel = $globalSettings->enableApi
    ? Yii::t('app', 'Use additional access token which is limited only to this terminal')
    : Yii::t('app', 'Enable importing and exporting documents via API');

// Вывести страницу API интеграции
echo $this->render(
    '@common/modules/autobot/views/shared/_apiIntegration',
    [
        'settings' => $terminalSettings,
        'enableCheckboxLabel' => $enableCheckboxLabel,
    ]
);
