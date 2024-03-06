<?php

use common\settings\AppSettings;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/** @var AppSettings $model */
$model = $data['model'];

$form = ActiveForm::begin([
    'id' => 'additionalTerminalsSettings'
]);
?>

    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                <?php
                    echo $form->field($model, 'useCompatibleSigning')->checkbox();
                    echo $form->field($model, 'useZipBeforeEncrypt')->checkbox();
                    echo $form->field($model, 'validateXmlOnImport')->checkbox();
                    echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);
                ?>
            </div>
        </div>
    </div>
<?php
ActiveForm::end();

$script = <<<JS
    // Подтверждение перед сохранением настроек
    $('body').on('submit', '#additionalTerminalsSettings', function() {
        var result = confirm("Внимание! Данные изменения отразятся на всех " +
         "терминалах данного сервера! Подтвердите действие.");

        if (result === false) {
            return false;
        }
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>