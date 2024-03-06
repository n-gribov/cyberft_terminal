<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<style type="text/css">
    .failed-login-table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 50%;
    }

    .failed-login-table td, .failed-login-table th {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
    }
</style>

<h3><?= $terminalId; ?>: <?= Yii::t('monitor/mailer', 'Notification'); ?></h3>

<p>
    <?= Yii::t('monitor/mailer', 'There are failed login attempts')?>
</p>

<table class="failed-login-table">
    <thead>
        <tr>
            <th>Id</th>
            <th><?=Yii::t('app/user', 'Пользователь')?></th>
            <th><?=Yii::t('app/user', 'E-mail')?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($userList as $user) : ?>
            <tr>
                <td><?=Html::a($user->id, Url::base() . "/user/view?id=" . $user->id) ?></td>
                <td><?=$user->name?></td>
                <td><?=$user->email?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
