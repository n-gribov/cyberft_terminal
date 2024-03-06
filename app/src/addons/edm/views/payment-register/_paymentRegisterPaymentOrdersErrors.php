<?php

/** @var yii\base\Model[] $models */
foreach ($models as $model) {
    if (!$model->hasErrors()) {
        continue;
    }
    echo "Ошибка в документе №{$model->number}:<br>";
    renderModelErrors($model);
    echo '<br>';
}

/**
 * @param $model yii\base\Model
 */
function renderModelErrors($model)
{
    echo '<ul>';
    foreach ($model->errors as $field => $errors) {
        echo sprintf(
            '<li>%s: %s</li>',
            $model->getAttributeLabel($field),
            implode(', ', $errors)
        );
    }
    echo '</ul>';
}
