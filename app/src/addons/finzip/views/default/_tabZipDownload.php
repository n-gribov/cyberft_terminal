<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var \common\document\Document $model */
// Получить роль пользователя из активной сессии
$isAdmin = in_array(Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN]);

$extModel = $model->extModel;
$hasAttachments = $extModel && ($extModel->fileCount > 0);
if ($model->isEncrypted && $extModel) {
    Yii::$app->exchange->setCurrentTerminalId($model->originTerminalId);

    $extModel->subject = $extModel->getEncryptedSubject();
    $extModel->descr = $extModel->getEncryptedDescr();
}
?>

<div class="panel">

<?php
// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
            'extModel.subject',
    [
        'format' => 'raw',
        'attribute' => 'extModel.descr',
        'value' => $extModel ? '<pre>' . Html::encode($extModel->descr) . '</pre>' : null
    ],
    ($extModel && $extModel->zipStoredFileId)
        ? ([
            'attribute' => 'zipStoredFileId',
            'label' => Yii::t('doc', 'Zip file'),
            'format' => 'html',
            'value' => $hasAttachments
                ? Html::a(
                    Yii::t('app', 'Download file'),
                    [
                        '/storage/download', 'id' => $extModel->zipStoredFileId,
                        'name' => 'finzip-' . $model->uuid . '.zip'
                    ],
                    ['class' => 'btn btn-primary']
                )
                : Yii::t( 'app','No attached documents')
                ])
        : ([
            'attribute' => 'zipStoredFileId',
            'label' => Yii::t('doc', 'Zip file'),
            'value' => Yii::t('other', 'File not yet processed'),
        ])
    ]
]);
?>
</div>
<?php
$this->registerCss(<<<CSS
    pre {
        background-color: white;
        border: none;
        color: black;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        padding: 0;
        white-space: pre-wrap;
    }
CSS
);
