<?php

use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictCurrency;
use common\widgets\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Organizations Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?=Html::a(Yii::t('app', 'Return'), ['index'], ['class' => 'btn btn-default'])?>
    <?=Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
    <?=Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data'  => [
            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
            'method'  => 'post',
        ],
    ])?>    
    <?=Html::a(Yii::t('edm', 'Add payer account'), ['edm-payer-account/create', 'name' => $model->name], 
            ['class' => 'btn btn-success'], 'ic-plus')?>
</p>

<?=DetailView::widget([
    'model'      => $model,
    'attributes' => [
        'typeLabel',
        [
            'attribute' => 'propertyType.name',
            'label' => Yii::t('edm', 'Property type'),
        ],
        'inn',
        'kpp',
        'ogrn',
        'dateEgrul',
        'address',
        'terminal.terminalId',
        [
            'label' => Yii::t('edm', 'Payee details validation'),
            'attribute' => 'disablePayeeDetailsValidation',
            'value' => $model->disablePayeeDetailsValidation ? Yii::t('app', 'No') : Yii::t('app', 'Yes')
        ],

    ],
])?>

<h4 class="dict-organization-address-title dict-organization-title " 
   style="cursor: pointer; color: #00529c;"><?= Yii::t('edm', 'Address') ?></h4>
<div class="dict-organization-address-block">
<?=DetailView::widget([
    'model'      => $model,
    'attributes' => [
        'state',
        'city',
        'street',
        'building',
        'district',
        'locality',
        'buildingNumber',
        'apartment'
    ],
])?>
</div>

<h4 class="dict-organization-details-title dict-organization-title" 
    style="cursor: pointer; color: #00529c;"><?= Yii::t('edm', 'Details in latin') ?></h4>
<div class="dict-organization-details-block">
<?=DetailView::widget([
    'model'      => $model,
    'attributes' => [
        [
            'label' => Yii::t('edm', 'Organization name'),
            'attribute' => 'nameLatin',
        ],
        'addressLatin',
        'locationLatin'
    ],
])?>
</div>

<?=GridView::widget([
    'dataProvider' => $accountsDataProvider,
    'filterModel'  => $accountsModel,
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn',
            'headerOptions' => [
                'style' => "width: 20px;",
            ],
        ],
        [
            'attribute' => 'name',
            'headerOptions' => [
                'style' => "width: 350px;",
            ],
        ],
        [
            'attribute' => 'number',
            'headerOptions' => [
                'style' => "width: 350px;",
            ],
        ],
        [
            'attribute' => 'currencyName',
            'filter' => ArrayHelper::map(DictCurrency::getValues(), 'id', 'name'),
            'value' => 'edmDictCurrencies.name',
            'label' => Yii::t('edm', 'Currency'),
            'headerOptions' => [
                'style' => "width: 100px;",
            ],
        ],
        [
            'attribute' => 'bankName',
            'label' => Yii::t('edm', 'Account bank'),
            'value' => 'bank.name',
            'headerOptions' => [
                'style' => "width: 350px;",
            ],
        ],
        [
            'attribute' => 'requireSignQty',
            'label' => Yii::t('app', 'Required signatures'),
            'value' => function($model) {
                return EdmHelper::getPayerAccountSignaturesNumber($model);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'controller' => 'edm-payer-account',
            'visibleButtons' => [
                'view' => function ($model, $key, $index) {
                    return true;
                },
                'update' => function ($model, $key, $index) {
                    return true;
                },
                'delete' => function ($model, $key, $index) {
                    return true;
                }
            ],
            'contentOptions' => [
                'style' => 'min-width: 125px;'
            ]
        ]
    ],
]);?>

<?php
        
$script = <<< JS
    // Колонки второй таблицы по ширине
    // должны соответстовать колонкам первой
    var thWidth = $('#w0 th:first').width();
    $('#w1 th:first').width(thWidth);
    $('#w2 th:first').width(thWidth);
        
    // (CYB-4440) Делаем заголовки блоков "Адрес" и "Реквизиты" синими по наведению.    
        
    var oldColor;    
        
    $('.dict-organization-title').hover(
        function(){
            $(this).css("text-decoration", "underline");
            $(this).css("color", "#002a50");
        },
        function(){
            $(this).css("text-decoration", "none");
            $(this).css("color", "#00529c");
        }
   );
        
    // (CYB-4440) Прячем блоки "Адрес" и "Реквизиты" по умолчанию. Показываем/прячем их по нажатию на заголовке.    
    $('.dict-organization-address-block').hide(); 
    $('.dict-organization-details-block').hide(); 
        
    $('body').on('click', '.dict-organization-address-title', function() {
        $('.dict-organization-address-block').slideToggle('400');
    });
    $('body').on('click', '.dict-organization-details-title', function() {
        $('.dict-organization-details-block').slideToggle('400');
    });

JS;

$this->registerJs($script, View::POS_READY);

?>
