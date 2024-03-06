<?php

use addons\swiftfin\models\documents\mt\mtUniversal\MtMask;
use addons\swiftfin\models\documents\mt\mtUniversal\Tag;
use addons\swiftfin\models\documents\mt\widgets\assets\TagAsset;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

TagAsset::register($this);

/* @var $model Tag */
/* @var $form ActiveForm */
/* @var $attribute string */
/* @var $disableContainer bool */
/* @var $scheme array */
/* @var $maskScheme array */

$attributeCount = count($model->attributes());
$scheme     = $model->getAttributeScheme($attribute);
$maskScheme = $model->getAttributeMaskScheme($attribute);
$title      = Yii::t('doc/mt', $model->getAttributeLabel($attribute));

$fieldOptions = [
	'readonly'    => (bool)$model->constant,
	'disabled'    => (bool)$model->constant,
	'placeholder' => $title,
	'title'       => "$title ({$maskScheme['mask']})",
];

if (!$maskScheme['isOptional'] && $attributeCount > 1) {
	$fieldOptions['class'] = 'mt-required-input';
}

if (isset($maskScheme['mask'])) {
	$fieldOptions['data-filter'] = MtMask::maskFilterRegexp($maskScheme['mask']);
}
if (!$maskScheme['prefix'] && !$maskScheme['code'] && !$maskScheme['postfix']) {
	$options = [
		'showLabels' => false,
	];
} else {
	$options = [
		'showLabels' => false,
		'addon' => [
			'prepend' => ($maskScheme['prefix'] . $maskScheme['code']
				? [
					'content' => $maskScheme['prefix'] . $maskScheme['code'],
					'options' => [
						//'class' => 'input-group-addon service'
					]
				]
				: null
			),
			'append' => ($maskScheme['postfix']
				?[
					'content' => $maskScheme['postfix'],
					'options' => [
						//'class' => 'input-group-addon service'
					]
				]
				: null
			),
		]
	];
    }

if (preg_match('/\v/', $maskScheme['delimiter'])) {
	print '<div class="linebreak"></div>';
}

if (isset($scheme['strict'])) {
	//unset($fieldOptions['data-filter']); // тут маски не нужны, на всякий случай убиваем
	$values = [null => $title];
	if (key($scheme['strict']) === 0) {
		$values = ArrayHelper::merge($values,
			array_combine($scheme['strict'], $scheme['strict'])
		);
	} else {
		$values = ArrayHelper::merge($values, $scheme['strict']);
	}

	print (!$disableContainer ? '<div class="col-sm-3">' : null);
	print $form
		->field($model, $attribute, $options)
		->dropDownList($values, $fieldOptions);
	print (!$disableContainer ? '</div>' : null);
} else if (isset($maskScheme['rows']) && $maskScheme['rows'] > 1) {
	$fieldOptions['rows'] = ($maskScheme['rows'] > 10 ? 10 : $maskScheme['rows']);

	print (!$disableContainer ? '<div class="col-sm-8">' : null);

    if ($maskScheme['rows'] && $maskScheme['length']) {

        if ($model->name == '72') {
            $fieldOptions['data-textarea-type'] = "72";
        }

        $fieldOptions['class'] = "mtmultiline";
        $fieldOptions['data-limit'] = $maskScheme['rows'] . ", " . $maskScheme['length'];

        if (isset($maskScheme['mask'])) {
            $fieldOptions['data-filter'] = MtMask::maskFilterRegexp($maskScheme['mask']);
        }
    }

	print $form
		->field($model, $attribute, $options)
		->textarea($fieldOptions);
	print (!$disableContainer ? '</div>' : null);
} else {

	/*
	//$inputSize = strlen($model->getAttributeLabel($attribute)) * 4;
	//$fieldOptions['style'] = 'min-width:'. $inputSize .'px;';

	$sectors = (int)ceil($maskScheme['length'] / 5);

	if ($sectors > 11) {
		$sectors = 11;
	} elseif ($sectors < 3) {
		$sectors = 3;
	}*/

    // Для полей определенной длины, своя логика подбора класса для ширины контейнера содержимого
    if ($maskScheme['length'] == 1) {
        $sectors = 2;
    } else if ($maskScheme['length'] == 34) {
        $sectors = 10;
    } else {
        switch ($attributeCount) {
            case 1 :
                $sectors = 8;
                break;
            case 2 :
                $sectors = 6;
                break;
            case 3 :
                $sectors = 4;
                break;
            case 4 :
                $sectors = 3;
                break;
            default:
                $sectors = 3;
        }
    }

	$fieldOptions['maxlength'] = $maskScheme['length'];

	print (!$disableContainer ? '<div class="col-sm-' . $sectors . '">' : null);

    if (!isset($scheme['type'])) {
        $scheme['type'] = '';
    }

    switch($scheme['type']) {
        case 'select2':
            $model->setAttribute($attribute, trim($model->$attribute));
            print $form->field($model, $attribute, $options)->widget(Select2::className(),
                [
                    'options'       => ['placeholder' => 'Identifier code'],
                    'pluginOptions' => [
                        'allowClear'         => true,
                        'minimumInputLength' => 1,
                        'ajax'               => [
                            'url'      => $scheme['dataUrl'],
                            'dataType' => 'json',
                            'delay'    => 250,
                            'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                        ],
                        'templateResult'     => new JsExpression('function(item) {
                            if (!item.swiftCode) return item.text;
                            return item.swiftCode + item.branchCode + " " + item.name;
						}'),
                        'templateSelection'  => new JsExpression('function(item) {
                            if (!item.swiftCode) return item.text;
                            return item.swiftCode + item.branchCode;
						}'),
                    ],
                    'pluginEvents' => [
                        'select2:selec' => 'function(e) { showInfo(e); }',
                        'change' => 'function(e) { removeInfo(e); }'
                    ]
                ]);
            break;
        default:
            print $form->field($model, $attribute, $options)->textInput($fieldOptions);
    }
	print (!$disableContainer ? '</div>' : null);
}

$this->registerJs(<<<JS

    function removeInfo(e) {
        var className = '.info-' + $(e.currentTarget)[0]['id'];
        $(className).html('');
    }

    function showInfo(e) {
        var select_val = $(e.currentTarget).val();

        $.ajax({
            url: '/swiftfin/dict-bank/get-bank-info',
            type: 'get',
            data: 'swiftInfo=' + select_val,
            success: function(res){
                if (res) {
                    info = JSON.parse(res);
                    var content = '<p><h5>' + info.name + '</h5></p>' + info.address + '<p></p>';
                    var grantParent = $(e.currentTarget).parent().parent().parent();
                    var className = "info-" + $(e.currentTarget)[0]['id'];
                    grantParent.append('<div class="col-sm-8 ' + className +'">'+ content +'</div>');
                }
            }
        });
    }
JS
, yii\web\View::POS_END
);

?>