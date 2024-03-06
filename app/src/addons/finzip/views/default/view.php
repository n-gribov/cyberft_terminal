<?php

use yii\web\View;

$from = Yii::$app->request->get('from');

switch ($from) {
    case 'forSigning':
        $backTitle = Yii::t('app/menu', 'Documents for signing');
        $backUrl = ['documents/signing-index'];
        break;
    default:
        $backTitle = Yii::t('app/menu', 'Free Format');
        $backUrl = ['index'];
        break;
}

if (isset($urlParams)) {
    $backUrl = array_merge($backUrl, $urlParams);
} else {
    $urlParams = [];
}

$this->title = Yii::t('app', 'View Free Format document') . ' #' . $model->id;

echo $this->render(
	'@common/views/document/_header',
	[
		'model' => $model,
		'referencingDataProvider' => $referencingDataProvider,
		'urlParams' => $urlParams,
		'mode' => $mode,
        'backUrl' => $backUrl,
		'dataView' => '@addons/finzip/views/default/_view',
	]
);

if (\Yii::$app->request->get('triggerSigning')) {
    $this->registerJs("$('#sign-documents-button').trigger('click');", View::POS_READY);
}