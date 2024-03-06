<?php

use common\models\CommonUserExt;
use addons\edm\models\EdmUserExt;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use common\widgets\GridView;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Additional settings for {modelClass}: ', [
        'modelClass' => Yii::t('app/user', 'user'),
    ]) . ' ' . $extModel->user->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['/user']];
$this->params['breadcrumbs'][] = ['label' => $extModel->user->name, 'url' => ['/user/view', 'id' => $extModel->user->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Update'), 'url' => ['/user/update', 'id' => $extModel->user->id]];
$this->params['breadcrumbs'][] = CommonUserExt::getServiceLabel($extModel->type);

$form = ActiveForm::begin([
    'action' => Url::toRoute(['update-permissions', 'id' => $extModel->id])
]);

$dataProvider = new ArrayDataProvider([
    'allModels' => $extModel->settingsLabels()[$extModel->type],
    'pagination' => false
]);
?>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-5">
                <?php
                $myGridWidget = GridView::begin([
                    'summary'      => '',
                    'dataProvider' => $dataProvider,
                    'showHeader'    => false,
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'header' => '',
                            'name' => 'settings',
                            'contentOptions'    => ['width' => '1%'],
                            'checkboxOptions' => function($item, $key) use ($extModel) {
                                return [
                                    'value' => $key,
                                    'checked' => is_array($extModel->settings) && in_array($key, $extModel->settings)
                                ];
                            }
                        ],
                        [
                            'value' => function($item) {
                                return $item;
                            }
                        ]
                    ],
                ]);
                $myGridWidget->end();
                ?>
            </div>
        </div>
        <div class="row">
            <input type="submit" value="<?=Yii::t('app', 'Save')?>" class="btn btn-success" style="margin-left:10px"/>
        </div>

    </div>
<?php
ActiveForm::end();
?>