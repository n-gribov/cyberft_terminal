<?php

use common\helpers\Html;

/** @var \yii\web\View $this */

echo Html::a('', '#', [
    'class' => 'btn-transport-info-button glyphicon glyphicon-info-sign',
    'title' => Yii::t('document', 'Transport information'),
    'data' => ['toggle' => 'modal', 'target' => '#transport-info-modal']
]);

$this->registerCss('
    .btn-transport-info-button {
        color: #4c4c4c;
    }
    .btn-transport-info-button:hover,
    .btn-transport-info-button:active,
    .btn-transport-info-button:visited,
    .btn-transport-info-button:focus {
        text-decoration: none;
    }
');
