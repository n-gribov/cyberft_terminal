<?php
$attributes = [
    'uuid',
    'dateCreate',
    'sender',
    'receiver',
    'statusLabel',
    'signaturesRequired',
    'signaturesCount'
];

// Создать детализированное представление
echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => $attributes
]);
