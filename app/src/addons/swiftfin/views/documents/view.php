<?php

use addons\swiftfin\helpers\SwiftfinHelper;

/** @var $this View */
/** @var $model Document */

$from = Yii::$app->request->get('from');

switch ($from) {
    case 'forSigning':
        $backTitle = Yii::t('app/menu', 'Documents for signing');
        $backUrl = ['signing-index'];
        break;
    case 'forAuthorization':
        $backTitle = Yii::t('app/menu', 'Documents for authorization');
        $backUrl = ['authorization-index'];
        break;
    case 'errors':
        $backTitle = Yii::t('other', 'SwiftFin Document Register');
        $backUrl = ['errors'];
        break;
    default:
        $backTitle = Yii::t('other', 'SwiftFin Document Register');
        $backUrl = ['index'];
        break;
}

if (isset($urlParams)) {
    $backUrl = array_merge($backUrl, $urlParams);
} else {
    $urlParams = [];
}

$this->title					 = Yii::t('doc', 'View document {type} #{id}', ['type' => $model->type, 'id' => $model->id]);
$this->params['breadcrumbs'][]   = ['label' => $backTitle, 'url' => $backUrl];
$this->params['breadcrumbs'][]	 = $this->title;

if (SwiftfinHelper::isAuthorizable($model, Yii::$app->user->identity->id)) {
    $customModuleActionView = '@addons/swiftfin/views/documents/_customAction';
} else {
    $customModuleActionView = null;
}

echo $this->render(
	'@common/views/document/_header',
	[
		'model' => $model,
		'referencingDataProvider' => $referencingDataProvider,
        'commandDataProvider' => $commandDataProvider,
		'urlParams' => $urlParams,
        'backUrl' => $backUrl,
		'mode' => $mode,
		'dataView' => '@addons/swiftfin/views/documents/_view',
        'customModuleActionView' => $customModuleActionView
	]
);
