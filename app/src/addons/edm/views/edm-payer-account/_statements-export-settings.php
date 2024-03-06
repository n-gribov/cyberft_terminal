<?php

use addons\edm\models\EdmPayerAccount;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var View $this */
/** @var EdmPayerAccount $model */

// Dirty hack to make radio button with empty value checked if field value is also empty
$createRadioListItemBuilder = function ($fieldValue) {
    return function ($index, $label, $name, $checked, $value) use ($fieldValue) {
        $reallyChecked = $checked || (empty($fieldValue) && empty($value));
        return Html::radio(
            $name,
            $reallyChecked,
            [
                'value' => $value,
                'label' => Html::encode($label),
            ]
        );
    };
}

?>

<h4>
    <?= Yii::t('edm', 'Statements export settings') ?>
</h4>

<?php $form = ActiveForm::begin([
    'action' => ['update-export-settings', 'id' => $model->id],
]) ?>

<?= $form
    ->field($model, 'previousDaysStatementsExportFormat')
    ->radioList(EdmPayerAccount::getStatementsExportFormatOptions(), ['item' => $createRadioListItemBuilder($model->previousDaysStatementsExportFormat)])
    ->label(Yii::t('edm', 'Previous period statements'))
?>
<?= $form
    ->field($model, 'todaysStatementsExportFormat')
    ->radioList(EdmPayerAccount::getStatementsExportFormatOptions(), ['item' => $createRadioListItemBuilder($model->todaysStatementsExportFormat)])
    ->label(Yii::t('edm', 'Current period statements'))
?>
<?= $form
    ->field($model, 'useIncrementalExportForTodaysStatements')
    ->checkbox()
?>

<div class="row">
    <div class="col-sm-2">
        <button type="submit" class="btn btn-primary btn-sm btn-block"><?= Yii::t('app', 'Save') ?></button>
    </div>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerCss(<<<CSS
    [role=radiogroup] label {
        font-weight: normal;
        display: block;   
    }
CSS
);
