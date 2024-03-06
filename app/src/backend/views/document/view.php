<?php

/** @var $this View */
/** @var $model Document */

$redirectUrl = Yii::$app->request->get('redirectUrl');

if ($redirectUrl) {
    $backUrl = [$redirectUrl];
} else {
    $backUrl = ['index'];
}

$backTitle = Yii::t('app', 'Documents');

if (isset($urlParams)) {
    $backUrl = array_merge($backUrl, $urlParams);
} else {
    $urlParams = [];
}

$this->title					 = Yii::t('doc', 'View document {type} #{id}', ['type' => $model->type, 'id' => $model->id]);
$this->params['breadcrumbs'][]   = ['label' => $backTitle, 'url' => $backUrl];
$this->params['breadcrumbs'][]	 = $this->title;

if (!isset($autobot)) {
    $autobot = null;
}

echo $this->render(
    '@common/views/document/_header',
    [
        'model' => $model,
        'referencingDataProvider' => $referencingDataProvider,
        'urlParams' => $urlParams,
        'backUrl' => $backUrl,
        'mode' => $mode,
        'autobot' => $autobot,
        'dataView' => $dataView,
        'actionView' => $actionView,
    ]
);
