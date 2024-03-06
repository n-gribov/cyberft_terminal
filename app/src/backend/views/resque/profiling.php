<?php

use common\widgets\GridView;

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'jobId',
        'jobName',
        'memoryUsageStart',
        'memoryUsageEnd',
        'time',
    ],
]) ?>
