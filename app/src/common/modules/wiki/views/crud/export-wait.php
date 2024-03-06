<?php

use common\modules\wiki\WikiModule;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$this->registerJs(<<<EOL
    setTimeout(function () {
        window.location.reload(1);
    }, 5000);
EOL
);

$this->title = WikiModule::t('default', 'Download wiki data');
$this->params['breadcrumbs'][] = ['label' => WikiModule::t('default', 'Documentation'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;

echo '<p>' . WikiModule::t('default', 'Archive will be downloaded soon') . '</p>';