<?php

use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use common\widgets\GridView;
use yii\helpers\Url;

/** @var \common\models\BaseUserExt $extModel */

$form = ActiveForm::begin([
	'action' => Url::toRoute(['update-permissions', 'id' => $extModel->user->id])
]);

$dataProvider = new ArrayDataProvider([
    'allModels' => $extModel->permissionLabels(),
    'pagination' => false
]);
?>
<div class="panel-body">
    <div class="row">
        <div class="col-lg-5">
        <?php
            // Создать таблицу для вывода
            $myGridWidget = GridView::begin([
                'summary'      => '',
                'dataProvider' => $dataProvider,
                'showHeader'    => false,
                'rowOptions' => function ($model) {
                    $options = [];

                    // Некоторые поля необходимо скрывать
                    $hideFields = [
                        Yii::t('app/user', 'Delete documents'),
                        Yii::t('app/user', 'Create documents'),
                        Yii::t('edm', 'Hide statements with null turnovers')
                    ];

                    if (in_array($model, $hideFields)) {
                        $options['style'] = "display: none";
                    }

                    return $options;
                },
                'columns' => [
                     [
                        'class'           => 'yii\grid\CheckboxColumn',
                        'header'          => '',
                        'name'            => 'permissions',
                        'contentOptions'  => ['width' => '1%'],
                        'checkboxOptions' => function($item, $key) use ($extModel) {
                            return [
                                'value' => $key,
                                'checked' => is_array($extModel->permissions) && in_array($key, $extModel->permissions)
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

