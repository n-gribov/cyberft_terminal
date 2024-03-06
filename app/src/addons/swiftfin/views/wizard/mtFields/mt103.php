<?php

use addons\swiftfin\models\documents\mt\MtBaseDocument;
use addons\swiftfin\models\documents\mt\Mt103Document;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var $form ActiveForm */
/* @var $model Mt103Document */

$this->registerJs(<<<JS
mtOptionsInit();
JS
	, yii\web\View::POS_READY);
?>

<?=
$form->field($model, 'senderReference', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('senderReference')],
	],
])->textInput()->label('* '.$model->getAttributeLabel('senderReference'));
?>
<?=
$form->field($model, 'timeIndication', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('timeIndication')],
	],
])->textInput()->label($model->getAttributeLabel('timeIndication'));
?>

<?=
$form->field($model, 'bankOperationCode', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('bankOperationCode')],
	],
])->dropDownList($model->getBankOperationCodeLabels(), [
		'prompt' => ''
	]
)->label('* '.$model->getAttributeLabel('bankOperationCode'))
?>

<?=
$form->field($model, 'instructionCode', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('instructionCode')],
	],
])->dropDownList($model->getInstructionCodeLabels(), [
		'prompt' => ''
	]
)
?>

<?=
$form->field($model, 'transactionTypeCode', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('transactionTypeCode')],
	],
])->textInput([])
?>

<?=$form->beginField($model, 'dateCurrencySettledAmount')?>
<?=Html::activeLabel($model, 'dateCurrencySettledAmount', [
	'class' => 'col-md-4 control-label',
	'label' => '* '.$model->getAttributeLabel('dateCurrencySettledAmount')
])?>
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-4">
				<div class="input-group">
					<span class="input-group-addon"><?=$model->getAttributeTag('dateCurrencySettledAmount')?></span>
					<?=MaskedInput::widget([
						'model'         => $model,
						'attribute'     => 'date',
						'mask'          => '999999',
						'clientOptions' => [
							'placeholder' => 'yymmdd',
							// 'alias' => 'date',
						]
					])?>
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
<?=$form->endField()?>

<?=$form->beginField($model, 'currencyInstructedAmount')?>
<?=Html::activeLabel($model, 'currencyInstructedAmount', [
	'class' => 'col-md-4 control-label',
	'label' => $model->getAttributeLabel('currencyInstructedAmount')
])?>
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-6">
				<div class="input-group">
					<span class="input-group-addon"><?=$model->getAttributeTag('currencyInstructedAmount')?></span>
					<?=Html::activeDropDownList($model, 'instructedCurrency', MtBaseDocument::$currencyIsoCodes, ['class' => 'form-control'])?>
				</div>
			</div>
			<div class="col-md-6">
				<?=Html::activeTextInput($model, 'instructedAmount', ['class' => 'form-control'])?>
			</div>
		</div>
	</div>
	<div class="col-md-offset-4 col-md-8">
		<div class="help-block"><?=Html::error($model, 'dateCurrencySettledAmount')?></div>
	</div>
<?=$form->endField()?>

<?=$form->field($model, 'exchangeRate', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('exchangeRate')],
	],
])->textInput([])
?>

<?=$this->render('commonMtFields/switchingRadio', [
	'model'       => $model, 'form' => $form,
	'radioList'   => ['A' => '50A', 'F' => '50F', 'K' => '50K'],
	'name'        => 'orderingCustomer',
	'mandatory'   => true,
	'fieldGroups' => [
		'A' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'text', 'suffix' => 'Text',],
		],
		'F' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
		],
		'K' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
		],
	],
])?>

<?=$form->field($model, 'sendingInstitution', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('sendingInstitution')],
	],
])->textInput([])
?>

<?=$this->render('commonMtFields/switchingRadio', [
	'model'       => $model, 'form' => $form,
	'radioList'   => ['A' => '52A', 'D' => '52D'],
	'name'        => 'orderingInstitution',
	'fieldGroups' => [
		'A' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'text', 'suffix' => 'Text',],
		],
		'D' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
		],
	],
])?>

<?=$this->render('commonMtFields/switchingRadio', [
	'model'       => $model, 'form' => $form,
	'radioList'   => ['A' => '53A', 'B' => '53B', 'D' => '53D'],
	'name'        => 'senderCorrespondent',
	'fieldGroups' => [
		'A' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'text', 'suffix' => 'Text',],
		],
		'B' => [
			['type' => 'text', 'suffix' => 'Req'],
			['type' => 'text', 'suffix' => 'Text'],
		],
		'D' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
		],
	],
])?>

<?=$this->render('commonMtFields/switchingRadio', [
	'model'       => $model, 'form' => $form,
	'radioList'   => ['A' => '54A', 'B' => '54B', 'D' => '54D'],
	'name'        => 'receiverCorrespondent',
	'fieldGroups' => [
		'A' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'text', 'suffix' => 'Text',],
		],
		'B' => [
			['type' => 'text', 'suffix' => 'Req'],
			['type' => 'text', 'suffix' => 'Text'],
		],
		'D' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
		],
	],
])?>

<?=$this->render('commonMtFields/switchingRadio', [
	'model'       => $model, 'form' => $form,
	'radioList'   => ['A' => '55A', 'B' => '55B', 'D' => '55D'],
	'name'        => 'thirdReimbursementInstitution',
	'fieldGroups' => [
		'A' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'text', 'suffix' => 'Text',],
		],
		'B' => [
			['type' => 'text', 'suffix' => 'Req'],
			['type' => 'text', 'suffix' => 'Text'],
		],
		'D' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
		],
	],
])?>

<?=$this->render('commonMtFields/switchingRadio', [
	'model'       => $model, 'form' => $form,
	'radioList'   => ['A' => '56A', 'D' => '56D'],
	'name'        => 'intermediaryInstitution',
	'fieldGroups' => [
		'A' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'text', 'suffix' => 'Text',],
		],
		'D' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
		],
	],
])?>

<?=$this->render('commonMtFields/switchingRadio', [
	'model'       => $model, 'form' => $form,
	'radioList'   => ['A' => '57A', 'D' => '57D'],
	'name'        => 'accountWithInstitution',
	'fieldGroups' => [
		'A' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'text', 'suffix' => 'Text',],
		],
		'D' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
		],
	],
])?>

<?=$this->render('commonMtFields/switchingRadio', [
	'model'       => $model, 'form' => $form,
	'radioList'   => ['A' => '59A', 'NLO' => '59 (No letter option)'],
	'name'        => 'beneficiaryCustomer',
	'mandatory'	  => true,
	'fieldGroups' => [
		'A'   => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'text', 'suffix' => 'Text',],
		],
		'NLO' => [
			['type' => 'text', 'suffix' => 'Req',],
			['type' => 'textarea', 'suffix' => 'Text', 'rows' => 5,],
		],
	],
])?>

<?=
$form->field($model, 'remittanceInformation', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('remittanceInformation')],
	],
])->textarea(['rows' => 5])
?>
<?=
$form->field($model, 'detailsOfCharges', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('detailsOfCharges')],
	],
])->dropDownList($model->getDetailsOfChargesLabels(), [
		'prompt' => ''
	]
)->label('* '.$model->getAttributeLabel('detailsOfCharges'))
?>
<?=
$form->field($model, 'senderCharges', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('senderCharges')],
	],
])->textInput([])
?>
<?=
$form->field($model, 'receiverCharges', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('receiverCharges')],
	],
])->textarea([])
?>
<?=
$form->field($model, 'senderToReceiverInformation', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('senderToReceiverInformation')],
	],
])->textarea(['rows' => 6])
?>
<?=
$form->field($model, 'regulatoryReporting', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('regulatoryReporting')],
	],
])->textarea(['rows' => 3])
?>
<?=
$form->field($model, 'envelopeContents', [
	'addon' => [
		'prepend' => ['content' => $model->getAttributeTag('envelopeContents')],
	],
])->textarea(['rows' => 3])
?>