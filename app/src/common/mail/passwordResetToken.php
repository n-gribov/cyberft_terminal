<?php

use common\models\User;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/reset-password', 'token' => $user->password_reset_token]);
?>

<p>Здравствуйте, <?= Html::encode($user->name) ?>,</p>
<p>Для вашей учетной записи на терминале CyberFT было запрошено восстановление пароля.</p>

<?php if ($user->status == User::STATUS_ACTIVE): ?>
    <p>Чтобы установить новый пароль, пройдите по этой ссылке: <?= Html::a(Html::encode($resetLink), $resetLink) ?>.</p>
<?php else: ?>
    <p>К сожалению, это невозможно сделать прямо сейчас, так как ваш аккаунт неактивен. Вам нужно обратиться к администратору терминала с просьбой активировать учетную запись, а после активации запросить восстановление пароля еще раз.</p>
<?php endif; ?>

<p>Если вы не запрашивали восстановление пароля, вам стоит сообщить о получении этого письма администратору терминала или технической поддержке.</p>
