<?php

use common\helpers\Address;
use common\modules\participant\models\BICDirParticipant;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\autobot\models\Autobot */

$this->title = Yii::t('app/autobot', 'View') . (empty($model->name) ? '' : ' - ' . $model->name);

$blockUrl = Url::to(['/autobot/block', 'id' => $model->id]);
$stopAndBlockUrl = Url::to(['/autobot/multiprocesses/stop-terminal-block-key',
    'autobotId' => $model->id, 'terminalId' => $model->controller->terminal->terminalId]);

$terminalId = $model->controller->terminal->terminalId;
$truncatedId = Address::truncateAddress($terminalId);
$participantData = BICDirParticipant::find()->where(['participantBIC' => $truncatedId])->one();

if ($participantData) {
    $terminalName = "{$terminalId} ($participantData->name)";
} else {
    $terminalName = $terminalId;
}
?>
<div class="autobot-view">
    <p id="buttons-block">
        <?= Html::a(Yii::t('app', 'Back'), $model->getListUrl(), ['class' => 'btn btn-default']) ?>
        <?php
            if ($model->isBlocked) {
                echo Html::a(Yii::t('app/autobot', 'Delete key'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app/autobot', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]);
            } else {
                if ($model->isActive && !$model->isUsedForSigning && !$model->isExpired()) {
                    echo Html::a(
                        Yii::t('app/autobot', 'Use for signing'),
                        ['/autobot/use-for-signing', 'id' => $model->id],
                        ['class' => 'btn btn-success']
                    );
                }
                echo Html::a(
                    Yii::t('app/autobot', 'Block'),
                    '',
                    ['class' => "btn btn-danger btn-block-autobot", 'data' => [
                        'block-url' => $blockUrl,
                        'block-stop-url' => $stopAndBlockUrl,
                        'terminal-id' => $model->controller->terminal->terminalId,
                        'status' => $model->status
                    ]]
                );
            }

            $isAdmin = Yii::$app->user->can('admin') || Yii::$app->user->can('additionalAdmin');
            if ($isAdmin) {
                echo Html::a(
                    'Сформировать акт о признании ЭП',
                    ['/certManager/acknowledgement-act/create', 'autobotId' => $model->id],
                    ['class' => 'btn btn-info pull-right']
                );
            }
        ?>
    </p>
    <div>
        <h4><?=Yii::t('app/autobot', 'Key parameters')?></h4>
        <?php
        // Создать детализированное представление
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'label' => Yii::t('app/autobot', 'Linked to the terminal'),
                    'value' => $terminalName
                ],
                [
                    'attribute' => 'status',
                    'value' => $model->getStatusLabel()
                ],
                'updatedAt',
            ],
        ]) ?>
    </div>
    <div>
        <h4><?=Yii::t('app/autobot', 'Key details')?></h4>
        <?php
        // Создать детализированное представление
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'ownerFullName',
                    'label' => Yii::t('app/autobot', 'Full Name'),
                    'value' => $model->controller->fullName
                ],
                'expirationDate',
                'organizationName',
                'localityName',
                'fingerprint',
            ],
        ]) ?>
    </div>
    <div>
        <h4><?=Yii::t('app/autobot', 'Key files')?></h4>
        <?php
        // Создать детализированное представление
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'certificate',
                    'value' => ' <a href="' . Url::toRoute(['download', 'id' => $model->id]). '">'
                        . '<span class="glyphicon glyphicon-download-alt"></span> '
                        . Yii::t('app', 'Download file')
                        . '</a>',
                    'format' => 'raw',
                    'label' => Yii::t('app/autobot', 'Certificate')
                ],
                [
                    'value' => ' <a href="' . Url::to(['/autobot/download-archive', 'id' => $model->id]) . '">'
                        . '<span class="glyphicon glyphicon-download-alt"></span> '
                        . Yii::t('app/autobot', 'Download archive')
                        . '</a>',
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Private key') . '<br>' . Yii::t('app/autobot', 'Public key') .
                        '<br>' . Yii::t('app/autobot', 'Certificate')
                ],
            ],
        ]) ?>
    </div>
</div>
<?php

// Вывести блок активации
echo $this->render('_activate');
// Вывести блок блокировки
echo $this->render('_block');

$this->registerJs(<<<JS
    var thWidth = $('#w0 th:first').width();
    $('#w1 th:first').width(thWidth);
    $('#w2 th:first').width(thWidth);
JS, yii\web\View::POS_READY);

$header = '<h4 class="modal-title">' . Yii::t('app/autobot', 'Settings') . '</h4>';
$footer = '<button type="button" class="btn btn-default" data-dismiss="modal">'
    . Yii::t('app', 'Close') . '</button>'
    . '<button type="button" class="btn btn-primary btn-submit-form">' . Yii::t('app', 'Save') . '</button>';

$modal = Modal::begin([
    'id' => 'settings-modal',
    'header' => $header,
    'footer' => $footer,
    'options' => [
        'tabindex' => false,
        'data' => [
            'backdrop' => 'static'
        ]
    ]
]);

$modal::end();

$this->registerCss(<<<CSS
    #buttons-block .btn {
        margin-right: .5em;
    }
CSS);
