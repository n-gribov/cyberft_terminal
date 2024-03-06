<?php

use yii\helpers\Html;

/** @var $controllers \common\modules\autobot\models\Controller[] */
/** @var $this \yii\web\View */

?>

<?php foreach ($controllers as $controller): ?>
<div class="panel panel-default autobot-key-owner-panel">
    <div class="panel-heading">
        <h2><?= Html::encode($controller->fullName) ?></h2>
        <div class="btn-group">
            <button type="button" class="btn btn-success"><?= Yii::t('app/autobot', 'Create key') ?></button>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="#"><?= Yii::t('app/autobot', 'Import key') ?></a></li>
            </ul>
        </div>

        <?php if ($controller->isEditable): ?>
            <div class="btn btn-info"><?= Yii::t('app', 'Edit') ?></div>
        <?php endif; ?>
        <?php if ($controller->isDeletable): ?>
            <div class="btn btn-danger"><?= Yii::t('app', 'Delete') ?></div>
        <?php endif; ?>
    </div>
    <?php if (count($controller->autobots) > 0): ?>
        <table class="table">
            <tr>
                <th><?= Yii::t('app/autobot', 'Fingerprint') ?></th>
                <th><?= Yii::t('app/autobot', 'Valid before') ?></th>
                <th><?= Yii::t('app/autobot', 'Status in terminal') ?></th>
                <th><?= Yii::t('app/autobot', 'Status in processing') ?></th>
                <th></th>
            </tr>
            <?php foreach ($controller->autobots as $autobot): ?>
                <tr>
                    <td><?= Html::encode($autobot->fingerprint) ?></td>
                    <td><?= Html::encode($autobot->expirationDate) ?></td>
                    <td><?= Html::encode($autobot->getStatusLabel()) ?></td>
                    <td><?= Html::encode($autobot->fingerprint) ?></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<?php
echo $this->render('_activate');

$this->registerCss(<<<CSS
.autobot-key-owner-panel .panel-heading {
    padding: 5px;
    min-height: 35px;
    box-sizing: content-box;
}
.autobot-key-owner-panel .panel-heading h2 {
    margin: 8px;
    font-size: 18px;
    float: left;
    color: black;
}
.autobot-key-owner-panel .panel-heading>.btn,
.autobot-key-owner-panel .panel-heading>.btn-group {
    float: right;
    margin-left: 5px;
}
.autobot-key-owner-panel .btn-group>.btn+.dropdown-toggle {
    padding-bottom: 7px;
}
CSS
);

?>