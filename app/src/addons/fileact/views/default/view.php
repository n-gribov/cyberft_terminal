<?php
$from = Yii::$app->request->get('from');

switch ($from) {
    case 'forSigning':
        $backTitle = Yii::t('app/menu', 'Documents for signing');
        $backUrl = ['documents/signing-index'];
        break;
    default:
        $backTitle = Yii::t('other', 'Document Register');
        $backUrl = ['index'];
        break;
}

if (isset($urlParams)) {
    $backUrl = array_merge($backUrl, $urlParams);
} else {
    $urlParams = [];
}

$this->title = Yii::t('other', 'View FileAct') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => $backTitle, 'url' => $backUrl];
$this->params['breadcrumbs'][] = $this->title;

// Вывести шапку документа
echo $this->render(
    '@common/views/document/_header',
    [
        'model' => $model,
        'referencingDataProvider' => $referencingDataProvider,
        'urlParams' => $urlParams,
        'backUrl' => $backUrl,
        'mode' => $mode,
        'dataView' => '@addons/fileact/views/default/_view'
    ]
);
