<?php

$backTitle = Yii::t('app', 'ISO20022');

// Проверяем get-параметр isoSubtype для получения корректной обратной ссылки на нужный журнал документов
$isoSubtype = Yii::$app->request->get('isoSubtype');

if (isset($isoSubtype)) {
    $backUrl = [$isoSubtype];
} else {
    $backUrl = ['index'];
}

if (isset($urlParams)) {
    $backUrl = array_merge($backUrl, $urlParams);
} else {
    $urlParams = [];
}

$this->title = Yii::t('doc', 'View document {type} #{id}', ['type' => $model->type, 'id' => $model->id]);
$this->params['breadcrumbs'][]   = ['label' => $backTitle, 'url' => $backUrl];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render(
    '@common/views/document/_header',
    [
        'model' => $model,
        'referencingDataProvider' => $referencingDataProvider,
        'urlParams' => isset($urlParams) ? $urlParams : null,
        'backUrl' => $backUrl,
        'mode' => $mode,
        'dataView' => '@addons/ISO20022/views/documents/_view',
        'showSignaturesMask' => $showSignaturesMask
    ]
);

?>