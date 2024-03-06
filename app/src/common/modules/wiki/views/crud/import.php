<?php

use common\modules\wiki\WikiModule;
use yii\widgets\ActiveForm;

$this->title = WikiModule::t('default', 'Import wiki data');
$this->params['breadcrumbs'][] = ['label' => WikiModule::t('default', 'Documentation'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?=kartik\file\FileInput::widget([
        'name'  => 'file',
        'pluginOptions' => [
            'showPreview' => true,
            'allowedFileExtensions' => ['zip'],
        ]
    ]) ?>

<?php ActiveForm::end() ?>
