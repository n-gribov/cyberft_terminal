<?php

/** @var \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest $model */

$this->registerCssFile("@web/css/edm/crr/printable.css");
$this->registerCssFile("@web/css/edm/crr/trade.css");

?>

<div class="container">
    <?php
        // Шапка
        echo $this->render('common/header', ['model' => $model]);

        // Секция 1
        echo $this->render('common/section1', ['model' => $model]);

        // Секция 2
        echo $this->render('common/section2', ['model' => $model]);

        // Секция 3
        echo $this->render('trade/section3', ['model' => $model]);

        // Секция 4
        echo $this->render('common/section4');

        // Секция 5
        echo $this->render('common/section5');

        // Секция 6
        echo $this->render('common/section6', ['model' => $model]);

        // Секция 7
        echo $this->render('common/section7');
    ?>
</div>