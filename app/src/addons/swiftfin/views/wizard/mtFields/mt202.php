<?php

use addons\swiftfin\models\documents\mt\MtBaseDocument;
use addons\swiftfin\models\documents\mt\Mt202Document;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var $form ActiveForm */
/* @var $model Mt202Document */

$this->registerJs('mtOptionsInit();', yii\web\View::POS_READY);

// 20A transactionReferenceNumber
// 21 relatedReference
?>

<?= $form->field($model, 'transactionReferenceNumber', [
    'addon' => [
        'prepend' => ['content' => $model->getAttributeTag('transactionReferenceNumber')],
    ],
])->textInput([])->label('* '.$model->getAttributeLabel('transactionReferenceNumber')) ?>

<?= $form->field($model, 'relatedReference', [
    'addon' => [
        'prepend' => ['content' => $model->getAttributeTag('relatedReference')],
    ],
])->textInput([])->label('* '.$model->getAttributeLabel('relatedReference')) ?>

<?= $form->field($model, 'timeIndication', [
    'addon' => [
        'prepend' => ['content' => $model->getAttributeTag('timeIndication')],
    ],
])->textInput()->label($model->getAttributeLabel('timeIndication')) ?>

<?= $form->beginField($model, 'dateCurrencySettledAmount')?>
<?= Html::activeLabel($model, 'dateCurrencySettledAmount', [
    'class' => 'col-md-4 control-label',
    'label' => '* '.$model->getAttributeLabel('dateCurrencySettledAmount')
]) ?>
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><?=$model->getAttributeTag('dateCurrencySettledAmount')?></span>
                    <?= MaskedInput::widget([
                        'model' => $model,
                        'attribute' => 'date',
                        'mask' => '999999',
                        'clientOptions'	=> [
                            'placeholder' => 'yymmdd',
                        ]
                    ]) ?>
                </div>
            </div>
            <div class="col-md-4">
                <?=Html::activeDropDownList($model, 'currency', MtBaseDocument::$currencyIsoCodes, ['class' => 'form-control'])?>
            </div>
            <div class="col-md-4">
                <?=Html::activeTextInput($model, 'settledAmount', ['class' => 'form-control'])?>
            </div>
        </div>
    </div>
    <div class="col-md-offset-4 col-md-8">
        <div class="help-block"><?=Html::error($model, 'dateCurrencySettledAmount')?></div>
    </div>
<?= $form->endField() ?>

<?= $this->render('commonMtFields/switchingRadio', [
    'model' => $model, 'form' => $form,
    'radioList' => ['A' => '52A', 'D' => '52D'],
    'name' => 'orderingInstitution',
    'fieldGroups' => [
        'A' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'text', 'suffix' => 'Text', /*'textInputMask' => '/9{20}INN9{10}',*/],
        ],
        'D' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'textarea', 'suffix' => 'Text', 'rows' => 4,],
        ],
    ],
]) ?>

<?= $this->render('commonMtFields/switchingRadio', [
    'model'       => $model, 'form' => $form,
    'radioList'   => ['A' => '53A', 'B' => '53B', 'D' => '53D'],
    'name'        => 'senderCorrespondent',
    'fieldGroups' => [
        'A' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'text', 'suffix' => 'Text', /*'textInputMask' => '/9{20}INN9{10}',*/],
        ],
        'B' => [
            ['type' => 'text', 'suffix' => 'Req'],
            ['type' => 'text', 'suffix' => 'Text'],
        ],
        'D' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
        ],
    ],
]) ?>

<?= $this->render('commonMtFields/switchingRadio', [
    'model'       => $model, 'form' => $form,
    'radioList'   => ['A' => '54A', 'B' => '54B', 'D' => '54D'],
    'name'        => 'receiverCorrespondent',
    'fieldGroups' => [
        'A' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'text', 'suffix' => 'Text', /*'textInputMask' => '/9{20}INN9{10}',*/],
        ],
        'B' => [
            ['type' => 'text', 'suffix' => 'Req'],
            ['type' => 'text', 'suffix' => 'Text'],
        ],
        'D' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
        ],
    ],
]) ?>

<?= $this->render('commonMtFields/switchingRadio', [
    'model' => $model, 'form' => $form,
    'radioList' => ['A' => '56A', 'D' => '56D'],
    'name' => 'intermediary',
    'fieldGroups' => [
        'A' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'text', 'suffix' => 'Text', /*'textInputMask' => '/9{20}INN9{10}',*/],
        ],
        'D' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'textarea', 'suffix' => 'Text', 'rows' => 4,],
        ],
    ],
]) ?>

<?= $this->render('commonMtFields/switchingRadio', [
    'model' => $model, 'form' => $form,
    'radioList' => ['A' => '57A', 'B' => '57B', 'D' => '57D'],
    'name' => 'accountWithInstitution',
    'fieldGroups' => [
        'A' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'text', 'suffix' => 'Text', /*'textInputMask' => '/9{20}INN9{10}',*/],
        ],
        'B' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'text', 'suffix' => 'Text', /*'textInputMask' => '/9{20}INN9{10}',*/],
        ],
        'D' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'textarea', 'suffix' => 'Text', 'rows' => 4,],
        ],
    ],
]) ?>

<?= $this->render('commonMtFields/switchingRadio', [
    'model' => $model, 'form' => $form,
    'radioList' => ['A' => '58A', 'D' => '58D'],
    'name' => 'beneficiaryInstitution',
    'mandatory'   => true,
    'fieldGroups' => [
        'A' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'text', 'suffix' => 'Text', /*'textInputMask' => '/9{20}INN9{10}',*/],
        ],
        'D' => [
            ['type' => 'text', 'suffix' => 'Req', /*'textInputMask' => '/9{20}INN9{10}',*/],
            ['type' => 'textarea', 'suffix' => 'Text', 'rows' => 4,],
        ],
    ],
])?>

<?= $form->field($model, 'senderToReceiverInformation', [
    'addon' => [
        'prepend' => ['content' => $model->getAttributeTag('senderToReceiverInformation')],
    ],
])->textarea(['rows' => 6]) ?>