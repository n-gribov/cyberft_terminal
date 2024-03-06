<?php

use common\modules\wiki\models\Page;
use vova07\imperavi\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

if (!empty($parent)) {
    echo $form->field($model, 'parent')->textInput([
        'value' => $parent->title,
        'readOnly' => true,
        'disabled' => true
        ]);
}
echo $form->field($model, 'title')->textInput(['maxlength' => 50]);
echo $form->field($model, 'slug')->textInput(['maxlength' => 50, 'readOnly' => true, 'disabled' => true]);
$wysiwygSettings = [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 100,
        'imageUpload' => Url::to(['crud/attach', 'pageId' => $model->id, 'type' => 'image']),
        'imageManagerJson' => Url::to(['crud/images-list', 'pageId' => $model->id]),
        'plugins' => [
            'clips',
            'fullscreen',
            'imagemanager',
        ]
    ]
];
echo $form->field($model, 'preview')->widget(Widget::className(), $wysiwygSettings);
echo $form->field($model, 'body')->widget(Widget::className(), ArrayHelper::merge(
    $wysiwygSettings,
    ['settings' => ['minHeight' => 500]]
));

//echo $form->field($model, 'version')->textInput(['maxlength' => 50]);

?>

<?=Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>

<?php ActiveForm::end();