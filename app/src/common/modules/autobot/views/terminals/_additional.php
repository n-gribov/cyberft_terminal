<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$model = $data['model'];
$terminalId = $data['terminalId'];

$form = ActiveForm::begin([
    'id' => 'additionalTerminalsSettings'
]);

echo Html::hiddenInput('terminalId', $terminalId);

?>
    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                <?php
                echo $form->field($model, 'useCompatibleSigning')->checkbox();
                echo $form->field($model, 'useZipBeforeEncrypt')->checkbox();
				echo $form->field($model, 'useUtf8ZipFilenameEncoding')->checkbox();
                echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);
                ?>
            </div>
        </div>
    </div>
<?php
ActiveForm::end();
?>