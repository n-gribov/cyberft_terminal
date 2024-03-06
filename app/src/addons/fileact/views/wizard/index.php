<?php
    $steps = [
        1 => [Yii::t('doc', 'Step {step}', ['step' => 1]), 'label'	=> Yii::t('doc', 'Recipient selection')],
        2 => [Yii::t('doc', 'Step {step}', ['step' => 2]), 'label'	=> Yii::t('doc', 'Document')],
        //3 => [Yii::t('doc', 'Step {step}', ['step' => 3]), 'label'	=> Yii::t('doc', 'Sign and send')],
    ];

    $this->title = Yii::t('app', 'Create document');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'FileAct'), 'url' => ['/fileact/default/index']];
    $navTitle = $this->title;

    if (isset($currentStep) && array_key_exists($currentStep, $steps)) {
        $navTitle .= ' (' . $steps[$currentStep][0] . ')';
    }

    $this->params['breadcrumbs'][] = $navTitle;
?>

<?php if (is_array($steps) && !empty($steps)) : ?>
    <ul class="nav nav-pills nav-justified">
        <?php foreach ($steps as $step => $stepData) : ?>
            <li class="<?= ($step == $currentStep)  ? 'active' : 'disabled' ?>"><a href="#"><?=$stepData[0] . '<br>' . $stepData['label']?></a></li>
        <?php endforeach ?>
    </ul>
    <br>
<?php endif ?>
    <hr>
<?php
    $renderSettings = ['model' => $model, 'currentStep' => $currentStep];

    // Вывести шаг визарда
    echo $this->render('step' . $currentStep, $renderSettings);

