<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var \common\modules\participant\models\BICDirParticipant $model */

$this->title = $model->participantBIC;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/participant', 'CyberFT Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
if (Yii::$app->user->can('admin')) {
    echo Html::a(
        Yii::t('app', 'Edit'),
        ['/participant/default/update', 'participantBIC' => $model->participantBIC],
        ['class' => 'btn btn-primary mb-1', 'style' => 'margin-bottom: 15px;']
    );
}
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'participantBIC',
        'providerBIC',
        'name',
        'institutionName',
        'type',
        'statusLabel',
        'documentFormatGroupLabel',
        'maxAttachmentSize',
    ],
]) ?>
