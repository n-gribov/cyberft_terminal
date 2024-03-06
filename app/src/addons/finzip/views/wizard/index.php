<?php
$steps = [
	1 => [Yii::t('doc', 'Step {step}', ['step' => 1]), 'label'	=> Yii::t('doc', 'Recipient selection')],
	2 => [Yii::t('doc', 'Step {step}', ['step' => 2]), 'label'	=> Yii::t('doc', 'Document')],
	3 => [Yii::t('doc', 'Step {step}', ['step' => 3]), 'label'	=> Yii::t('doc', 'Signing and sending')],
];

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Create Free Format document');

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
<?php

    echo $this->render('step' . $currentStep, [
        'model'       => $model,
        'currentStep' => $currentStep,
        'dataProvider' => $dataProvider ?? null,
        'userCanSignDocuments' => $userCanSignDocuments ?? null,
        'signNum' => $signNum ?? null,
        'data' => $data ?? null
    ]);
?>
