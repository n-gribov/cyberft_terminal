<li class="dropdown">
    <a title="<?= Yii::t('app/profile', 'Terminals not running') ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        <span class="glyphicon glyphicon-warning-sign"></span>
        <span class="badge badge-notify"><?= $countNotify ?></span>
    </a>
    <ul class="dropdown-menu" role="menu">
        <li><a><nobr><b><?= Yii::t('app/profile', 'Terminals not running') ?>:</b></nobr></a></li>
        <?php foreach($stoppedTerminals as $terminalId) : ?>
            <li><a <?php if ($isAdmin) { echo 'href="/autobot/multiprocesses/index"'; }?>><?= $terminalId ?></a></li>
        <?php endforeach ?>
    </ul>
</li>