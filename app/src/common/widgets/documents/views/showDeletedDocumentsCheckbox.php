<?php

use common\document\DocumentSearch;
use yii\bootstrap\ActiveForm;

/** @var \yii\web\View $this */
/** @var DocumentSearch $filterModel */

?>

<div class="pull-right" style="margin-right:1em">

<?php $form = ActiveForm::begin([
    'id' => $filterModel->formName(),
    'method' => 'get',
    'fieldConfig' => [
        'options' => [
            'tag' => false,
        ],
    ],
    'options' => [
        'style' => 'display: inline;',
    ],
]);

?>

<span class="form-group">
    <?= $form
        ->field(
            $filterModel,
            'showDeleted',
            ['checkboxTemplate' => '<label>{input} {labelTitle}</label>']
        )
        ->checkbox([
            'onChange' => '$("#' . $filterModel->formName() . '").submit();',
        ])
    ?>
</span>

<?php ActiveForm::end(); ?>

</div>
