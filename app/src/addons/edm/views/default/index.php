<?php

use yii\bootstrap\Html;

$this->title = Yii::t('edm', 'Banking');

$this->params['breadcrumbs'][] = $this->title;

$menu = require Yii::getAlias('@addons/edm/config/menu.php');

echo "<ul>";
foreach ($menu['items'] as $item) {
    if (Yii::$app->user->can($item['permission'], $item['permissionParams'] ?? [])) {
        echo "<li>" . Html::a(Yii::t('app/menu', $item['label']), $item['url']) . "</li>";
    }
}
echo "</ul>";
