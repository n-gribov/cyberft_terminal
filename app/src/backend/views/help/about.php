<?php

use common\helpers\Project;

/* @var $this yii\web\View */

$this->title                   = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = $this->title;

// Основной терминал
$primaryTerminal = Yii::$app->exchange->getPrimaryTerminal();
?>

<?=
Yii::t('app', '{appname}, version {version} {tag}, primary terminal {terminalId}',
    [
    'appname' => Yii::$app->name,
    'version' => Yii::$app->version,
    'tag'     => ($gitInfo  = Project::gitInfo()) ? "($gitInfo)" : '',
    'terminalId' => $primaryTerminal->terminalId
]);
?>

