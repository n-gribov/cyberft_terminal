<li class="dropdown">
    <a title="<?= Yii::t('app', 'Documents for signing') ?>"
       class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"
       href="#">
        <span class="glyphicon glyphicon-edit"></span><span class="badge badge-notify"><?= $countNotify ?></span></a>
    <ul class="dropdown-menu" role="menu">
        <li><a><nobr><b><?= Yii::t('app', 'Documents for signing') ?></b></nobr></a></li>
        <?php foreach($docList as $title => $url) : ?>
            <li><a href="<?= $url ?>"><?= $title ?></a></li>
        <?php endforeach ?>
    </ul>
</li>