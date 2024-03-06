<?php

use common\modules\wiki\WikiModule;
use yii\helpers\Html;

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => WikiModule::t('default', 'Documentation'), 'url' => ['default/index']];
if (!empty($parents = $model->parents)) {
    krsort($parents);
    foreach ($parents as $parent) {
        $this->params['breadcrumbs'][] = [
            'label' => $parent->title,
            'url'   => $parent->getUrl()
        ];
    }
}
$this->params['breadcrumbs'][] = $this->title;
$hasChildren = $model->hasChildren();
if (Yii::$app->user->can('pageManage')) {
    $this->beginBlock('pageActions');
    if ($hasChildren) {
        $msg = WikiModule::t('default', 'PAGE_SUBPAGE_DELETE_WARNING');
    } else {
        $msg = WikiModule::t('default', 'PAGE_DELETE_WARNING');
    }
    echo Html::a(
            Yii::t('app', 'Delete'),
            ['crud/delete', 'id' => $model->id],
            [
                'class' => 'btn btn-danger',
                'onClick' => 'return confirm("' . $msg . '")'
            ]
    );
    echo ' ' . Html::a(Yii::t('app', 'Update'), ['crud/update', 'id' => $model->id], ['class' => 'btn btn-success']);
    echo ' ' . Html::a(Yii::t('app', 'Create'), ['crud/create', 'parentId' => $model->id], ['class' => 'btn btn-warning']);
    $this->endBlock('pageActions');
}
?>
<div class="row">
    <?php if ($model->hasChildren()) :?>
    <div class="panel panel-left panel-default pull-left">
        <div class="panel-heading"><?= WikiModule::t('default', 'Nested pages') ?></div>
        <div class="panel-body">
            <ul>
            <?php foreach ($model->children as $child) : ?>
                <li>
                    <a href="<?=$child->getUrl()?>"><?=$child->title?></a>
                </li>
            <?php endforeach ?>
            </ul>
        </div>
    </div>
    <?php endif ?>
    <?= $model->body ?>
</div>