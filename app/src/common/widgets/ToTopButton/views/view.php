<a href="#" class="to-top-button-widget btn btn-primary">
    <span class="glyphicon glyphicon-menu-up"></span>
</a>

<?php

$this->registerCss('
    .to-top-button-widget {
        display: none;
        position: fixed;
        right: 50px;
        bottom: 50px;
    }
');

$script = <<< JS
    // Событие нажатия на кнопку, для поднятия в верхнюю часть окна
    $('.to-top-button-widget').on('click', function(e) {
        e.preventDefault();
        $("html, body").animate({scrollTop: 0}, "slow");
        return false;
    });

    // Событие прокрутки окна
    $(window).scroll(function() {
        buttonVisibility();
    });

    // Отображение/скрытие кнопки после прокрутки экрана
    function buttonVisibility() {
        var window_top = $(window).scrollTop();
        if (window_top > 450) {
            $('.to-top-button-widget').css({display: "block"});
        } else {
            $('.to-top-button-widget').css({display: "none"});
        }
    }

    buttonVisibility();

JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>