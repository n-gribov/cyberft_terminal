<?php

use common\widgets\language\LanguageSwitcher;

/** @var \yii\web\View $this */

?>

<li id="language-dropdown" class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        <span class="flag-<?= Yii::$app->language ?>"></span><?= Yii::t('app', Yii::$app->language) ?>
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <?= LanguageSwitcher::widget(); ?>
    </ul>
</li>

<?php

$this->registerJs(<<<JS
    var languageDropDown = $('#language-dropdown');
    languageDropDown.find('.dropdown-menu').css('min-width', languageDropDown.width()); 
JS
);
