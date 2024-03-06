<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use common\widgets\GridView;

$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();

$dataProvider = $data['dataProvider'];

/** @var \common\settings\ExportSettings $exportSettings */
$exportSettings = $data['exportSettings'];

/** @var \common\settings\AppSettings $appSettings */
$appSettings = $data['appSettings'];

$useGlobalExportSettingsOptions = [
    1 => Yii::t('app', 'Export documents to common directory (.../cyberft/app/export)'),
    0 => Yii::t('app', 'Export documents to this terminal directory (.../cyberft/app/export/{terminalAddress})', ['terminalAddress' => $terminal->terminalId]),
];

?>

    <div class="row">
        <div class="col-md-12">
            <?= $form
                ->field($appSettings, 'useGlobalExportSettings')
                ->radioList($useGlobalExportSettingsOptions)
                ->label(false);
            ?>
        </div>
    </div>

    <?= Html::beginTag(
        'fieldset',
        [
            'id' => 'terminal-export-settings',
            'class' => $appSettings->useGlobalExportSettings ? 'hidden' : '',
            'disabled' => (bool)$appSettings->useGlobalExportSettings
        ])
    ?>

    <div class="row">
        <div class="col-md-12">
            <?=$form->field($exportSettings, 'useSwiftfinFormat')->checkbox() ?>
        </div>
    </div>

    <div class="row" style="margin-bottom: 25px">
        <div class="col-md-12">
            <?=$form->field($exportSettings, 'exportStatusReports')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

            <?php

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'title',
                        'label' => Yii::t('app', 'Module'),
                        'options' => [
                            'width' => 150,
                        ],
                    ],
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'header' => Yii::t('app', 'Activate XML export'),
                        'name' => 'exportXml',
                        'options' => [
                            'width' => 150
                        ],
                        'checkboxOptions' => function($model) {
                            return [
                                'value' => $model['module'],
                                'checked' => $model['exportXml'],
                            ];
                        }
                    ],
                ],
            ]);

            ?>
        </div>
    </div>

    <?= Html::endTag('fieldset') ?>

    <div class="row">
        <div class="col-sm-2">
            <p><?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-block']) ?></p>
        </div>
    </div>
<?php ActiveForm::end()?>

<style>
    .checkbox {
        margin-bottom: 0;
        margin-top: 0;
    }
</style>

<?php
$this->registerJs(<<<JS
    $('input:radio[name="AppSettings[useGlobalExportSettings]"]').on('change', function() {
        if (!this.checked) {
            return;
        }
        var useGlobalSettings = this.value === '1';
        $('#terminal-export-settings')
            .prop('disabled', useGlobalSettings)
            .toggleClass('hidden', useGlobalSettings);
    });
JS
);