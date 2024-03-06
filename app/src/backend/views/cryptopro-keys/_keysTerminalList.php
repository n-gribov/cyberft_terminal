<?php
use common\widgets\GridView;
use common\helpers\Html;

$optionsTerminalId = [
    'id' => 'terminal-select',
    'class' => 'form-control'
];

if (count($terminalList) > 1) {
    $optionsTerminalId['prompt'] = Yii::t('app', 'All');
} else {
    $optionsTerminalId['disabled'] = 'disabled';
}

?>

<div class="row" style="margin-bottom: 15px;">
    <div class="col-lg-10">
        <?= Html::label(Yii::t('app/cert', 'Terminals that use this key'))?>
        <?= Html::dropDownList('terminalsList', null, $terminalList, $optionsTerminalId)?>
    </div>
    <div class="col-lg-1">
        <a id="add-user-terminal" href="#" class="btn btn-primary" data-id="<?=$keyId?>"><?= Yii::t('app', 'Add') ?></a>
    </div>
</div>

<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'user-terminals-list',
    'columns' => [
        [
            'label' => 'Терминал',
            'value' => 'terminalCharacterId'
        ],
        [
            'label' => 'Наименование',
            'value' => 'title'
        ],
        [
            'format' => 'raw',
            'value' => function($model) use ($keyId) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#',
                    [
                        'class' => 'delete-terminal',
                        'data' => [
                            'key-id' => $keyId,
                            'terminal-id' => $model['terminalId']
                        ],
                    ]);
            }
        ],
    ],
]);
