<?php

use yii\helpers\Url;
use common\widgets\InlineHelp\InlineHelp;

$steps = [
    1 => [Yii::t('doc', 'Step {step}', ['step' => 1]), 'label'	=> Yii::t('doc', 'Recipient selection')],
    2 => [Yii::t('doc', 'Step {step}', ['step' => 2]), 'label'	=> Yii::t('doc', 'Document')],
    3 => [Yii::t('doc', 'Step {step}', ['step' => 3]), 'label'	=> Yii::t('doc', 'Confirm')],
];

$this->title = Yii::t('app', 'Create document');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Purchase/Sale currency'), 'url' => Url::toRoute(['/edm/foreign-currency-operation-wizard'])];
$navTitle = $this->title;

if (isset($currentStep) && array_key_exists($currentStep, $steps)) {
    $navTitle .= ' (' . $steps[$currentStep][0] . ')';
}

$this->params['breadcrumbs'][] = $navTitle;

?>

<div class="pull-right">
    <?=InlineHelp::widget(['widgetId' => 'edm-foreign-currency-operation-wizard', 'setClassList' => ['edm-journal-wiki-widget']]);?>
</div>

<?php if (is_array($steps) && !empty($steps)) :?>
    <ul class="nav nav-pills nav-justified">
        <?php foreach ($steps as $step => $stepData) : ?>
            <li class="<?= ($step == $currentStep)  ? 'active' : 'disabled' ?>"><a href="#"><?=$stepData[0] . '<br />' . $stepData['label']?></a></li>
        <?php endforeach ?>
    </ul>
    <br/>
<?php endif ?>
<?=$this->render('step' . $currentStep, [
    'model'       => $model,
    'currentStep' => $currentStep,
    'dataProvider' => isset($dataProvider) ? $dataProvider : null,
    'data' => isset($data) ? $data : null
])?>
