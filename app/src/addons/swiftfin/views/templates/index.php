<?php

use common\helpers\Html;
use common\widgets\GridView;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('doc', 'Document Templates');
$this->params['breadcrumbs'][] = $this->title;
?>

<p class="pull-left">
    <?= Html::a(Yii::t('doc', 'Create template'), ['create'], ['class' => 'btn btn-success']) ?>
</p>

<p class="pull-right">
    <?php

    echo Html::a('',
        '#',
        [
            'class' => 'btn-columns-settings glyphicon glyphicon-cog',
            'title' => Yii::t('app', 'Columns settings')
        ]
    );
    ?>
</p>

<?php

$columns['docType'] = ['attribute' => 'docType'];
$columns['title'] = ['attribute' => 'title'];
$columns['sender'] = ['attribute' => 'sender'];
$columns['recipient'] = ['attribute' => 'recipient'];
$columns['bankPriority'] = ['attribute' => 'bankPriority'];

$columnsEnabled = [];

// Колонка с порядковым номером строки
$columnsEnabled['serial'] = ['class' => 'yii\grid\SerialColumn'];

// Получение колонок, которые могут быть отображены
$columnsSettings = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

foreach($columnsSettings as $setting => $value) {
    $columnsEnabled[$setting] = $value;
}

// Обязательные колонки, которые должны отображаться в любом случае
$columnsEnabled['actions'] = ['class' => 'yii\grid\ActionColumn'];

$columnsEnabled['create'] = [
    'label' => Yii::t('app','Create document'),
    'format' => 'html',
    'value' => function($model) {
        return Html::a('<span class="glyphicon glyphicon glyphicon glyphicon-open-file"></span>',
            ['create-swiftfin', 'id' => $model->id]
        );
    }
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $columnsEnabled
]);

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $model
    ]
);

?>
