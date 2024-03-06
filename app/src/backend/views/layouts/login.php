<?php

use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
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
    <![endif]-->

    <!--[if lte IE 9]>
    <script src="/js/flexibility.js"></script>
    <![endif]-->

    <?php $this->head() ?>
</head>

<body id="login-page">
<?php $this->beginBody() ?>

<div class="content-wrapper">
    <div class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-collapse collapse navbar-right" id="navbar-main">
                <ul class="nav navbar-nav navbar-right">
                    <?= $this->render('_languageDropDown') ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="logo"></div>
        <?= $content ?>
    </div>
    <?= $this->render('_footer', ['alignCenter' => true]) ?>
</div>

<?php
$this->registerJs(<<<JS
    $(document).ready(function () {
        var checkPlaceholder = function (input) {
            if (input.val() === '' && !input.is(':focus')) {
                input.addClass('placeholder-shown');
            } else {
                input.removeClass('placeholder-shown');
            }
        };
        
        var inputs = $('.form-control');
        inputs.change(function() {
            checkPlaceholder($(this));
        });
        inputs.each(function(i, input) {
            checkPlaceholder($(input));
        });
        inputs.focusin(function () {
            $(this).removeClass('placeholder-shown');
        });
        inputs.focusout(function () {
            if ($(this).val() === '') {
                $(this).addClass('placeholder-shown');
            }
        });
    });
JS

)
?>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
