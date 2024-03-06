<?php

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var \common\modules\autobot\models\ProcessingSettingsForm $model */
$model = $data['settingsForm'];
$runningTerminals = $data['runningTerminals'];
$availableProcessings = $data['availableProcessings'];

$processingSelectOptions = array_reduce(
    $availableProcessings,
    function($carry, \common\models\Processing $processing) {
        $carry[$processing->id] = "{$processing->name} ({$processing->dsn})";
        return $carry;
    },
    []
);

$inputStatus = empty($runningTerminals) ? [] : ['disabled' => true];

$form = ActiveForm::begin(['method' => 'post']);
?>

<div class="form-group">
    <?= $form->field($model, 'processingId')
        ->dropDownList($processingSelectOptions, $inputStatus)
        ->label(Yii::t('app/terminal', 'Processing URL')); ?>

    <?= $form->field($model, 'safeMode')->checkBox(
        ArrayHelper::merge(
            $inputStatus,
            ['label' => Yii::t('app/terminal', 'Restrict sending files over 1.5 MB')]
        )
    ); ?>

</div>
<div class="form-group">
    <?php if (!empty($runningTerminals)) : ?>
        <div class="alert alert-danger"><?=Yii::t('app/terminal', 'Changing of network configuration is not possible while automatic processing {termId} is running', ['termId' => implode(',', $runningTerminals)])?></div>
    <?php else : ?>
        <?=Html::submitButton(Yii::t('app', 'Save'), [
            'class'        => 'btn btn-danger',
            'data-confirm' => Yii::t('app/terminal', 'Are you sure you want to change network settings?'),
        ])?>
        <?=Html::a(Yii::t('app/terminal', 'Restore'),
            'processing-settings-reload',
            [
                'class'        => 'btn btn-primary',
                'data-confirm' => Yii::t('app/terminal', 'Are you sure you want to reload settings?'),
            ])?>

    <?php endif ?>
</div>

<?php ActiveForm::end(); ?>
