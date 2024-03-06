<?php

use common\helpers\Html;
use common\modules\wiki\WikiModule;

$this->title = WikiModule::t('default', 'Documentation');

$this->params['breadcrumbs'][] = $this->title;


if (Yii::$app->user->can('pageManage')) {

    $this->beginBlock('pageActions');

    echo Html::a(WikiModule::t('default', 'Create page'), ['crud/create'],
        ['class' => 'btn btn-success']) . ' ';
    
    echo Html::a(WikiModule::t('default', 'Export'), ['crud/export'],
        ['class' => 'btn btn-primary']) . ' ';

    echo Html::a(WikiModule::t('default', 'Import'), ['crud/import'],
        ['class' => 'btn btn-warning']) . ' ';

    $this->endBlock('pageActions');
}
?>

<h3><?= WikiModule::t('default', 'Download documentation') ?></h3>
<ul>
    <li><a href="http://download.cyberft.ru/Documentation/Transport_client%20module%20CyberFT%20(instalation%20guide).pdf" ><?= Yii::t('app',
        'Installation manual') ?> </a></li>
    <li><a href="http://download.cyberft.ru/Documentation/Transport%20client%20module%20CyberFT%20(administrator%27s%20guide).pdf" ><?= Yii::t('app',
        'Administrator\'s guide') ?></a></li>
    <li><a href="http://download.cyberft.ru/Documentation/Transport%20client%20module%20CyberFT%20(user%27s%20guide).pdf"><?= Yii::t('app',
        'User guide') ?></a></li>
</ul>

<?php if (!empty($pages)) :?>
    <h3><?= WikiModule::t('default', 'Help') ?></h3>
    <ol>
    <?php foreach ($pages as $page): ?>
        <li><?= Html::a($page->title, ['default/view', 'slug' => $page->slug])?></li>
    <?php endforeach  ?>
    </ol>
<?php endif ?>