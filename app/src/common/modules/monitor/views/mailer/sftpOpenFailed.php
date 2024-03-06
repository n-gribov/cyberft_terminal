<?php

use common\helpers\NumericHelper;

$suffix = NumericHelper::getPluralSuffix($count);
?>

<h3><?= $terminalId; ?>: <?= \Yii::t('monitor/events', 'Failed to open SFTP resource'); ?></h3>

<?= \Yii::t('monitor/mailer',
        'There {suffix, plural, =1{is 1 error} other{are {count} errors}} on open SFTP resources',
        ['count' => $count, 'suffix' => $suffix])?>
