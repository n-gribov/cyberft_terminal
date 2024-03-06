<h3>Failed jobs</h3>

<?php
if (empty($stat['failed'])) {
    echo "<p>No failed jobs</p>";
} else {
    echo \common\widgets\GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $stat['failed'],
            ]),
        'columns' => ['failed_at', 'payload.class', 'error']
    ]);
}
?>

<h3>Queued jobs</h3>
<?php
if (empty($stat['queued'])) {
    echo "<p>No queued jobs</p>";
} else {
    foreach ($stat['queued'] as $queue => $jobs) {
        echo \common\widgets\GridView::widget([
            'caption' => $queue,
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $jobs,
                ]),
            'columns' => [
                'payload.class',
                [
                    'attribute' => 'payload.args',
                    'content' => function ($model, $key, $index, $column) use($jobs) {
                        return print_r($jobs[$key]->payload['args'], true);
                    }
                ]
            ]
        ]);
    }
}
?>