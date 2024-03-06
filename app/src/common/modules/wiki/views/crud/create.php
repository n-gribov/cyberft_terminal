<?php

use common\modules\wiki\WikiModule;

$this->title = WikiModule::t('default', 'Create page');

$this->params['breadcrumbs'][] = ['label' => WikiModule::t('default', 'Documentation'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;

// Вывести форму
echo $this->render('_form', [
    'model' => $model,
    'parent' => $parent
]);