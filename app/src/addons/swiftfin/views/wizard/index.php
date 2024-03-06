<?php
$steps = [
	1 => Yii::t('doc', 'Title'),
	2 => Yii::t('doc', 'Document'),
	3 => Yii::t('doc', 'Confirm'),
    4 => Yii::t('doc', 'Sign and send'),
];

/* @var $this View */
/* @var $searchModel Document */
/* @var $dataProvider ActiveDataProvider */

$this->title                   = (!empty($documentId)) ? Yii::t('app', 'Edit document') : Yii::t('app', 'Create document');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['/swiftfin/documents/index']];
$navTitle = $this->title;

if (isset($currentStep) && array_key_exists($currentStep, $steps)) {
	$navTitle .= ' (' . $steps[$currentStep] . ')';
}
$this->params['breadcrumbs'][] = $navTitle;
?>

<?php if (is_array($steps) && !empty($steps)) :?>
    <ul class="nav nav-pills nav-justified">
    <?php foreach ($steps as $step => $stepData) : ?>
        <li class="<?= ($step == $currentStep)  ? 'active' : 'disabled' ?>"><a href="#"><?=$stepData?></a></li>
    <?php endforeach ?>
    </ul>
    <br/>
<?php endif ?>
<hr/>
<?=$this->render('step' . $currentStep, [
    'model'       => $model,
    'currentStep' => $currentStep,
    'viewMode'    => isset($viewMode) ? $viewMode : null,
    'formable'    => isset($formable) && $formable,
    'textEdit'    => isset($textEdit) && $textEdit,
    'data'	      => isset($data) ? $data : '',
])?>
