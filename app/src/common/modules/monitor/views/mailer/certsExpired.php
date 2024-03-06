<?php

use common\modules\monitor\checkers\CertsExpiredChecker;
use common\helpers\Html;
use yii\helpers\Url;

?>

<style type="text/css">
    .certs-expired-table {
        border-spacing: 0;
    }

    .certs-expired-table td {
        border: 1px solid #000;
        padding: 5px;
    }

    .certs-expired-table .data-row td {
        font-weight: bold;
    }
</style>

<?php if (!empty($certs)) { ?>
    <?php foreach($certs as $group => $type) {?>
        <h3><?=$group?>:</h3>
            <?php foreach($type as $label => $content) {?>
                <h4><?=$label?></h4>

                <table class="certs-expired-table">
                    <tr>
                        <td width="10%">ID Терминала</td>
                        <td width="20%">Организация</td>
                        <td>Владелец ключа</td>
                        <td>Отпечаток сертификата</td>
                        <td>Дата окончания</td>
                    </tr>
                    <?php foreach ($content as $id => $cert) {?>
                        <tr class="data-row">
                            <td><?=$cert['terminal']?></td>
                            <td><?=$cert['terminalName']?></td>
                            <td><?=$cert['owner']?></td>
                            <td><?=$cert['fingerprint']?></td>
                            <td><?=$cert['date']?></td>
                        </tr>
                    <?php } ?>
                </table>

            <?php } ?>
            <hr>
    <?php } ?>
<?php } ?>

<?= \Yii::t('monitor/mailer', 'To renew keys please contact at the following address support@cyberft.ru'); ?>


