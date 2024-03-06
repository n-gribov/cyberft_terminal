<?php

use common\modules\autobot\models\Autobot;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $data array */
/** @var $controllers \common\modules\autobot\models\Controller[] */

$controllers = $data['controllers'];
$terminalId = $data['terminalId'];

?>

    <div style="margin-bottom: 15px;">
        <?= Html::a(
            Yii::t('app/autobot', 'Create controller'),
            ['/autobot/terminal-controller/create', 'terminalId' => $terminalId],
            [
                'class' => 'btn btn-success',
                'data' => ['load-modal' => true],
            ]
        ); ?>
    </div>

<?php foreach ($controllers as $controller): ?>
    <div class="panel panel-default controller-panel">
        <div class="panel-heading">
            <h2><?= Html::encode($controller->fullName ?: Yii::t('app/autobot', '(no name)')) ?></h2>
            <?php if ($controller->isDeletable): ?>
                <?= Html::a(
                    '<span class="glyphicon glyphicon-trash"></span>',
                    ['/autobot/terminal-controller/delete', 'id' => $controller->id],
                    [
                        'class' => 'btn btn-link text-success',
                        'data' => [
                            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'method' => 'post'
                        ]
                    ]
                ); ?>
            <?php endif; ?>
            <?php if ($controller->isEditable): ?>
                <?= Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>',
                    ['/autobot/terminal-controller/update', 'id' => $controller->id],
                    [
                        'class' => 'btn btn-link text-success',
                        'data' => ['load-modal' => true],
                    ]
                ); ?>
            <?php endif; ?>
        </div>
        <?php if (count($controller->autobots) > 0): ?>
            <table class="table table-hover autobots-table">
                <colgroup>
                    <col>
                    <col style="width: 200px">
                    <col style="width: 200px">
                    <col style="width: 200px">
                </colgroup>
                <thead>
                <tr>
                    <th><?= Yii::t('app/autobot', 'Fingerprint') ?></th>
                    <th><?= Yii::t('app/autobot', 'Valid before') ?></th>
                    <th><?= Yii::t('app', 'Status') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($controller->autobots as $autobot): ?>
                    <?php
                    $rowClass = '';
                    if ($autobot->status === Autobot::STATUS_USED_FOR_SIGNING) {
                        $rowClass = 'success';
                    } elseif ($autobot->isExpired()) {
                        $rowClass = 'danger';
                    }
                    ?>
                    <tr class="<?= $rowClass ?>" data-id="<?= $autobot->id ?>">
                        <td><?= Html::encode($autobot->fingerprint) ?></td>
                        <td><?= Yii::$app->formatter->asDate($autobot->expirationDate) ?></td>
                        <td><?= Html::encode($autobot->getStatusLabel()) ?></td>
                        <td class="action-column">
                            <?php
                            if ($autobot->isActive && !$autobot->isUsedForSigning && !$autobot->isExpired()) {
                                echo Html::a(
                                    Yii::t('app/autobot', 'Use for signing'),
                                    ['/autobot/use-for-signing', 'id' => $autobot->id],
                                    ['class' => 'btn btn-success btn-control']
                                );
                            }
                            ?>
                            <?= Html::a(
                                '<span class="glyphicon glyphicon-download-alt"></span>',
                                ['/autobot/download', 'id' => $autobot->id]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <div class="buttons-block">
            <?= Html::a(
                '<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app/autobot', 'Create key'),
                ['/autobot/create', 'controllerId' => $controller->id],
                [
                    'data' => ['load-modal' => true],
                ]
            ); ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-import"></span> ' . Yii::t('app/autobot', 'Import key'),
                ['/autobot/import', 'controllerId' => $controller->id],
                [
                    'data' => ['load-modal' => true],
                ]
            ); ?>
        </div>
    </div>
<?php endforeach; ?>

<div id="modal-placeholder"></div>

<?php
//echo $this->render('_activate');

$this->registerCss(<<<CSS
.controller-panel {
    margin-bottom: 3em;
}
.buttons-block {
    height: 40px;
    padding: 3px 8px;
}
.buttons-block a {
    margin-right: 10px;
    margin-left: 5px;
    line-height: 35px;
}
.buttons-block a .glyphicon {
    font-size: 16px;
}
.controller-panel .panel-heading {
    padding: 5px;
    min-height: 35px;
    box-sizing: content-box;
}
.controller-panel .panel-heading h2 {
    margin: 8px;
    font-size: 18px;
    float: left;
    color: black;
}
.controller-panel .panel-heading > .btn,
.controller-panel .panel-heading > .btn-group {
    float: right;
    margin-left: 5px;
}
.controller-panel .table {
    font-size: 13px;
    border-bottom: 1px solid #ddd;
}
.autobots-table tbody tr {
    cursor: pointer;
}
.btn-link.text-success {
    color: #2b8f0e;
}
.controller-panel .btn-link{
    padding: 7px 0;
}
.autobots-table tbody > tr > td, .autobots-table > thead > tr th {
    height: 40px;
    padding: 4px 8px;
    vertical-align: middle;
    line-height: 15px;
}
.autobots-table .action-column {
    white-space: nowrap;
    text-align: right;
}
.autobots-table .action-column a {
    margin-left: 10px;
}
.autobots-table .action-column .btn-success {
    padding: 5px 10px !important;
}
CSS
);

$this->registerJs(<<<JS
$('.autobots-table tbody tr').on('click', function() {
    location.href = '/autobot/default/view?id=' + $(this).data('id');  
});
JS
);
