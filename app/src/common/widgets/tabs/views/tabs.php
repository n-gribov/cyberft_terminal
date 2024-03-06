<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $items */

$removeLanguageFromUrl = function ($url) {
    return preg_replace('#^/(ru|en)/#', '/', $url);
};

$isActiveUrl = function ($url) use ($removeLanguageFromUrl) {
    $url = $removeLanguageFromUrl($url);
    $currentUrl = $removeLanguageFromUrl(Url::to());
    return $currentUrl === $url || strpos($currentUrl, "$url&") === 0 || strpos($currentUrl, "$url?") === 0;
};

?>

<ul class="nav nav-tabs">
    <?php foreach ($items as $item): ?>
        <?php
        $url = Url::to($item['url']);
        $isActive = $item['isActive'] ?? $isActiveUrl($url);
        ?>
        <li role="presentation" class="<?= $isActive ? 'active' : '' ?>">
            <a href="<?= $url ?>"><?= Html::encode($item['title']) ?></a>
        </li>
    <?php endforeach; ?>
</ul>
