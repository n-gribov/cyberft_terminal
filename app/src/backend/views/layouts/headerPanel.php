<?php
use common\models\User;
use common\models\UserTerminal;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\HeaderNotify\HeaderNotifyWidget;
?>
<style>
.badge-notify{
   background:red;
   position:relative;
   top: 10px;
   left: -5px;
}
</style>
<?php
    $isLoggedIn = !Yii::$app->user->isGuest;
    // Получить роль пользователя из активной сессии
    $userRole = $isLoggedIn ? Yii::$app->user->identity->role : null;
    $isAdmin = $userRole === User::ROLE_ADMIN;
    $isAdditionalAdmin = $userRole == User::ROLE_ADDITIONAL_ADMIN;
    $isUser = $userRole == User::ROLE_USER;
?>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <?php if (!Yii::$app->user->isGuest) : ?>
                <button id="toggle" class="navbar-toggle no-collapse pull-left" type="button">
                    <span class="ic-menu"></span>
                </button>
            <?php endif ?>
            <?php
                // Получение основного терминала пользователя
                $primaryTerminal = Yii::$app->exchange->getPrimaryTerminal();
            ?>
            <div class="dropdown brand-dropdown pull-left">
                <a class="navbar-brand" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?= Html::encode($primaryTerminal->screenName)?>
                    <?= $isUser || $isAdditionalAdmin ? '<span class="caret"></span>' : '' ?>
                </a>

                <?php if (!$isUser && !$isAdditionalAdmin) : ?>
                    <p class="navbar-text">
                        <span title="<?= Html::encode($primaryTerminal->terminalId)?>">
                            <?= Html::encode($primaryTerminal->terminalId)?>
                        </span>
                    </p>
                <?php endif ?>

                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="ic-lockoff"></span>
                </button>
                <?php
                    $userTerminals = ($isUser || $isAdditionalAdmin) && !Yii::$app->user->identity->disableTerminalSelect
                        ? UserTerminal::getUserTerminals(Yii::$app->user->id)
                        : [];

                    if (count($userTerminals)) :
                ?>
                    <ul class="dropdown-menu">
                        <?php foreach($userTerminals as $terminal) : ?>
                            <li>
                                <a href="<?= Url::toRoute(['/site/switch-terminal', 'id' => $terminal->id]) ?>">
                                    <span class="terminal-name"><?= Html::encode($terminal->screenName)?></span>
                                    <span class="description"><?= Html::encode($terminal->terminalId)?></span>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>

                <?php endif ?>
            </div>
        </div>
        <div class="navbar-collapse collapse navbar-right" id="navbar-main">
            <ul class="nav navbar-nav navbar-right">
                <?php
                    // Уведомления
                    if ($isUser) {
                        // Документы к подписанию
                        echo HeaderNotifyWidget::widget(['type' => HeaderNotifyWidget::TYPE_FOR_SIGNING]);
                        // Новые документы
                        echo HeaderNotifyWidget::widget(['type' => HeaderNotifyWidget::TYPE_NEW]);
                        // Незапущенные терминалы пользователя
                        echo HeaderNotifyWidget::widget(['type' => HeaderNotifyWidget::TYPE_STOPPED_TERMINALS_USER]);
                    } else if ($isAdmin || $isAdditionalAdmin) {
                        // Незапущенные терминалы админа
                        echo HeaderNotifyWidget::widget(['type' => HeaderNotifyWidget::TYPE_STOPPED_TERMINALS_ADMIN]);
                    }
                ?>
                <?= // Вывести языковую панель
                    $this->render('_languageDropDown') ?>
                <li class="dropdown">
                    <?php if (!Yii::$app->user->isGuest) : ?>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="ic-lockoff"></span> <?=Yii::$app->user->identity->screenName?> <span class="caret"></span>
                        </a>
                    <?php else : ?>
                        <a href="<?=Url::toRoute('site/login')?>" title="<?= Yii::t('app', 'Log in') ?>"><?= Yii::t('app', 'Log in') ?> <i class="fa fa-sign-in"></i></a>
                    <?php endif ?>
                    <?php if (!Yii::$app->user->isGuest): ?>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?=Url::toRoute('/user/profile')?>"><?=Yii::t('app', 'Logged in as')?>&nbsp;<strong><?=Yii::$app->user->identity->screenName?></strong></a></li>
                            <li><a href="#"><?=Yii::t('app', 'Your role')?>&nbsp;<strong><?=Yii::$app->user->identity->roleLabel;?></strong></a></li>
                            <?php if (Yii::$app->user->identity->role == User::ROLE_USER) : ?>
                                <li><a href="#"><?=Yii::t('app', 'Your signature number')?>&nbsp;<strong><?=Yii::$app->user->identity->signatureNumberLabel?></strong></a></li>
                            <?php endif ?>
                            <li><a href="<?=Url::toRoute('/site/logout')?>"><?= Yii::t('app', 'Log out') ?></a></li>
                        </ul>
                    <?php endif ?>
                </li>
            </ul>
        </div>
    </div>
</div>