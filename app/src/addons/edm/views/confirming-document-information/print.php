<?php

/** @var \yii\web\View $this */

// Вывести страницу
echo $this->render(
    '_view',
    ['model' => $model, 'attachedFiles' => [], 'signatures' => $signatures]
);
