<?php

use common\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var integer $documentId */
/** @var string $deleteUrl */

ActiveForm::begin([
    'action'  => Url::toRoute($deleteUrl, ['method' => 'post']),
    'method'  => 'post',
    'options' => [
        'id'    => 'delete-document-form',
        'style' => 'display: inline;',
    ],
]);

echo Html::hiddenInput('id[]', $documentId);

echo Html::submitButton(
    Yii::t('app', 'Delete'),
    [
        'class'        => 'btn btn-danger',
        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?')
    ]
);

ActiveForm::end();
