<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use common\models\User;
use common\helpers\UserHelper;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="../assets/css/ie8.css"  />
    <script src="../vendor/js/html5shiv.min.js"></script>
    <script src="../vendor/js/respond.min.js"></script>
    <!--[endif]-->
    <?php if (!Yii::$app->user->isGuest) : ?>
        <script>
            window.App = { userLogoutTimeout: <?=UserHelper::getUserLogoutTimeout()?> };
        </script>
    <?php endif ?>
    <?php $this->head() ?>
</head>

<body class="left-sidebar">
<?php $this->beginBody() ?>
<?php include(__DIR__ . '/headerPanel.php') ?>
<div class="wrapper<?= (Yii::$app->user->isGuest) ? ' toggled' : '' ?>">
    <!-- Sidebar-start -->
    <?php if (!Yii::$app->user->isGuest): ?>
    <div class="sidebar-left">
        <a href="<?= yii\helpers\Url::toRoute(['/']) ?>" class="logo-cyberft-ink"></a>
        <ul class="nav accordion" id="mainmenu">
            <?php
            if (!empty(Yii::$app->menu) && !empty(Yii::$app->menu->items)) {
                // Получить роль пользователя из активной сессии
                if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role !== User::ROLE_CONTROLLER) {
                    echo \backend\widgets\Nav::widget([
                        'items'		=> Yii::$app->menu->items,
                        'activateParents'	=> true
                    ]);
                }
                // Получить роль пользователя из активной сессии
                if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role === User::ROLE_CONTROLLER) {
                    echo \backend\widgets\Nav::widget([
                        'items' => [
                            [
                                'id' => 'Documents',
                                'label' => 'Documents',
                                'url' => ['/document/index'],
                                'permission' => 'commonDocumentAdmin',
                                'menuId' => 'document',
                                'iconClass' => 'ic-case',
                                'items' => [
                                    [
                                        'label' => 'Documents for sending',
                                        'url' => ['/document/controller-verification'],
                                        'permission' => 'documentControllerVerification',
                                    ],
                                ],
                            ],
                        ]
                    ]);
                }
            }
            ?>
        </ul>
    </div>
    <!-- Sidebar-end -->
    <?php endif ?>
    <div class="content">
        <!-- Content-start -->
        <div class="container-fluid">
            <?php
                // Для главной страницы пользователя не отображаем заголовок
                if (Yii::$app->requestedRoute !== 'profile/dashboard') : ?>
                <div class="page-header bs-component page-header-custom">
                    <div class="row valign">
                        <div class="col-xs-6">
                            <?php if ($this->title) : ?>
                                <h1 class="main-h1"><?= Html::encode($this->title) ?></h1>
                            <?php endif ?>
                        </div>
                        <div class="col-xs-6 text-right">
                            <?php if (isset($this->blocks['pageActions'])) : ?>
                                <?= $this->blocks['pageActions'] ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if (isset($this->blocks['pageAdditional'])) : ?>
                <div class="row block-page-additional">
                    <div class="col-sm-12 text-right">
                        <?= $this->blocks['pageAdditional'] ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="well well-content">
                <?= \common\widgets\Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
        <!-- Content-end -->
        <?= // Вывести колонтитул
            $this->render('_footer') ?>
    </div>
</div>
<div id="source-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Source Code</h4>
            </div>
            <div class="modal-body">
                <pre><code class="language-markup"></code></pre>
            </div>
        </div>
    </div>
</div>
<?php
if (Yii::$app->session->getFlash('checkPostLoginNotificationModalCerts', false)) {
    $urlCerts = yii\helpers\Url::toRoute(['/site/get-post-login-notification-modal-certs']);
    $certsScript = <<<JS
        $.get('{$urlCerts}', function (response) {
            if (response.modalContent) {
                $(response.modalContent)
                    .appendTo($('body'))
                    .modal('show');
            }
        });
    JS;
} else {
    $certsScript = '';
}

if (Yii::$app->session->getFlash('checkPostLoginNotificationModalLetters', false)) {
    $urlLetters = yii\helpers\Url::toRoute(['/site/get-post-login-notification-modal-letters']);
    $this->registerJs(<<<JS
        $.get('{$urlLetters}', function (response) {
            if (response.modalContent) {
                response.modalContent.forEach(append);
                $certsScript
            } else {
                $certsScript
            }
        });
        function append(item) {
            $(item)
                .appendTo($('body'))
                .modal('show');
        }
    JS);
} else {
    $this->registerJs($certsScript);
}
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>