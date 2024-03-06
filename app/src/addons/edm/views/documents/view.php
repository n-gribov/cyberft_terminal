<?php

use common\models\User;

$this->title = Yii::t('edm', 'View document #{id}', ['id' => $model->id]);

$from = Yii::$app->request->get('from');

switch ($from) {
    case 'statementPaymentOrder':
        $backTitle = Yii::t('edm', 'Statement registry');
        $backUrl = ['view', 'id' => $model->id, 'from' => 'statement'];
        break;
    case 'statement':
        $backTitle = Yii::t('edm', 'Statement registry');
        $backUrl = ['statement'];
        break;
    case 'correction':
        $backTitle = Yii::t('edm', 'Correction registry');
        $backUrl = ['correction'];
        break;
    case 'paymentOrder':
        $backTitle = Yii::t('edm', 'Payment order registry');
        $backUrl = ['payment-order'];
        break;
    case 'forSigning':
        $backTitle = Yii::t('edm', 'Documents for signing');
        $backUrl = ['signing-index'];
        break;
    default:
        $backTitle = Yii::t('edm', 'Banking registry');
        $backUrl = Yii::$app->user->identity->role == User::ROLE_ADMIN ? ['index'] : ['payment-order'];
        break;
}

if (isset($urlParams)) {
    $backUrl = array_merge($backUrl, $urlParams);
    $urlParams['from'] = $from;
} else {
    $urlParams = [];
}

$this->params['breadcrumbs'][]   = ['label' => $backTitle, 'url' => $backUrl];
$this->params['breadcrumbs'][]   = $this->title;

$renderParams = [
    'model' => $model,
    'referencingDataProvider' => $referencingDataProvider,
    'urlParams' => isset($urlParams) ? $urlParams : null,
    'mode' => $mode,
    'backUrl' => $backUrl,
    'num' => isset($num) ? $num : null,
    'dataView' => $dataView
];

if (isset($actionView)) {
    $renderParams['actionView'] = $actionView;
}

// Вывести блок заголовка документа
echo $this->render('@common/views/document/_header', $renderParams);
