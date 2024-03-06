<?php
use common\widgets\GridView;
use common\helpers\Html;

$optionsTerminalId = [
    'id' => 'beneficiary-select',
    'class' => 'form-control'
];

if (count($beneficiaryList) > 1) {
    $optionsTerminalId['prompt'] = Yii::t('app', 'All');
} else {
    $optionsTerminalId['disabled'] = 'disabled';
}

?>

<div class="row">
    <div class="col-lg-9">
        <?= Html::label(Yii::t('app/cert', 'Beneficiaries for which the key is used'))?>
    </div>
</div>
<div class="row" style="margin-bottom: 15px;">
    <div class="col-lg-9" >
        <?= Html::dropDownList('beneficiaryNotSelected', null, [], $optionsTerminalId)?>
    </div>    
    <div class="col-lg-3">
        <a id="add-user-beneficiary" href="#" class="btn btn-primary" data-id="<?=$keyId?>"><?= Yii::t('app', 'Add') ?></a>        
    </div>
</div>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProviderBeneficiary,
    'id' => 'user-terminals-list',
    'columns' => [
        [
            'label' => 'Терминал',
            'value' => 'terminalId'
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
                        'class' => 'delete-beneficiary',
                        'data' => [
                            'key-id' => $keyId,
                            'beneficiary-id' => $model['terminalId']
                        ],
                    ]);
            }
        ],
    ],
]);

