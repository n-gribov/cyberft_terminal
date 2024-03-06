<?php

use addons\fileact\FileActModule;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $filterModel \addons\fileact\models\FileActSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => Url::toRoute(['default/index'])];
$this->params['breadcrumbs'][] = $this->title;

/**
 * Параметр необходимый для
 * корректной работы кнопки Назад
 * на странице просмотра документа
 */

$urlParams['from'] = "forSigning";

$userCanDeleteDocuments = Yii::$app->user->can(DocumentPermission::DELETE, ['serviceId' => FileActModule::SERVICE_ID]);
$deletableDocumentsIds = [];

if ($userCanDeleteDocuments) {
    $deletableDocumentsIds = array_reduce(
        $dataProvider->models,
        function ($carry, Document $document) {
            if ($document->isDeletable()) {
                $carry[] = $document->id;
            }
            return $carry;
        },
        []
    );

    if (count($deletableDocumentsIds) > 0) {
        echo DeleteSelectedDocumentsButton::widget(['checkboxesSelector' => '.delete-checkbox, .select-on-check-all']);
    }
}

echo $this->render('_search', [
    'model' => $filterModel,
    'filterStatus' => $filterStatus,
]);

echo $this->render('_list', [
    'filterModel'  => $filterModel,
    'dataProvider' => $dataProvider,
    'urlParams' => $urlParams,
    'userCanDeleteDocuments' => $userCanDeleteDocuments,
    'deletableDocumentsIds' => $deletableDocumentsIds,
    'listType' => $listType
]);
