<?php

$alignCenter = $alignCenter ?? false;
$contentClass = $alignCenter ? 'text-center' : 'pull-right';

?>

<div class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">
                    <li class="<?= $contentClass ?>">© 1997-<?= date('Y') ?> <a href="<?= Yii::t('app', 'https://cyberplat.com') ?>"><?= Yii::t('app', 'CyberPlat LLC') ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
