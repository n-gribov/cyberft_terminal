<h3>Смена статусов сертификатов</h3>

<?php

// Формируем таблицу с информацией по смене статусов сертификатов

?>

<style type="text/css">

    .certs-status-table {
        border-spacing: 0;
        margin: 10px 0px;
    }

    .certs-status-table td,
    .certs-status-table th {
        border: 1px solid black;
        padding: 5px;
    }

</style>

<?php if (!empty($certs)) { ?>

    <table class="certs-status-table">

        <thead>
        <th>#</th>
        <th>Имя</th>
        <th>Статус</th>
        <th>Причина смены</th>
        </thead>

        <?php $i = 0; ?>

        <?php foreach($certs as $cert) {?>

            <?php $i++;?>

            <tr>
                <td>
                    <?=$i?>
                </td>
                <td>
                    <?=$cert['certName']?>
                </td>
                <td>
                    <?=$cert['status']?>
                </td>
                <td>
                    <?=$cert['reason']?>
                </td>
            </tr>

        <?php } ?>

    </table>

<?php } ?>
