<?php

use addons\edm\EdmModule;
use common\document\DocumentPermission;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictContractor */

$this->title = $model->name;

$userCanCreateDocuments = Yii::$app->user->can(
    DocumentPermission::CREATE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => '*',
    ]
);
?>
<p>
    <?=Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default'])?>
    <?php if ($userCanCreateDocuments) { ?>
        <?=Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
        <?=Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method'  => 'post',
            ],
        ])?>
    <?php } ?>
</p>

<?=$this->render('_view', ['model' => $model]) ?>