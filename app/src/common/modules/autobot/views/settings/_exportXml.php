<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use common\widgets\GridView;

$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();

$dataProvider = $data['dataProvider'];
$swiftfinSettings = $data['swiftfinSettings'];

$app = Yii::$app->settings->get('app');
?>
    <div class="row">
        <div class="col-md-12">
            <?=$form->field($swiftfinSettings, 'exportIsActive')->checkbox() ?>
        </div>
    </div>

    <div class="row" style="margin-bottom: 25px">
        <div class="col-md-12">
            <?= $form->field($app, 'exportStatusReports')->checkbox() ?>
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
