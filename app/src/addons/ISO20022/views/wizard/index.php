<?php
$steps = [
	1 => [Yii::t('doc', 'Step {step}', ['step' => 1]), 'label'	=> Yii::t('doc', 'Recipient selection')],
	2 => [Yii::t('doc', 'Step {step}', ['step' => 2]), 'label'	=> Yii::t('doc', 'Document')],
	3 => [Yii::t('doc', 'Step {step}', ['step' => 3]), 'label'	=> Yii::t('doc', 'Confirm')],
];

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app/menu', 'Create free format document');

?>

<?php if (is_array($steps) && !empty($steps)) :?>
    <ul class="nav nav-pills nav-justified">
        <?php foreach ($steps as $step => $stepData) : ?>
            <li class="<?= ($step == $currentStep) ? 'active' : 'disabled' ?>"><a href="#"><?=$stepData[0] . '<br />' . $stepData['label']?></a></li>
        <?php endforeach ?>
    </ul>
    <br/>
<?php endif ?>
<hr/>
<?=$this->render('step' . $currentStep, [
    'model'       => $model,
    'currentStep' => $currentStep,
    'data' => isset($data) ? $data : null,
    'settings' => isset($settings) ? $settings : null
])?>

<?php

$script = <<< JS
    $('.iso-form').change(function() {
        $.post('/wizard-cache/iso-free-format', $(this).serialize());
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>
