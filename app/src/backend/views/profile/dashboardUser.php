<?php

/** @var yii\web\View $this */
/** @var \addons\edm\models\EdmPayerAccount[] $accounts */

$this->title = Yii::t('app/menu', 'Statistics');

?>

<style>
    .counters h3 > a {color: inherit;}
</style>

<div class="counters">
    <div class="dashboard-account-block">
        <?php if (count($accounts) > 0) { ?>
            <?php
                // Вывод информации по счетам
                echo $this->render('_edmAccounts', ['accounts' => $accounts]);
            ?>
        <?php } ?>
    </div>
    <div class="dashboard-today-block">
        <?php
            $todayBlocks = [];

            if (isset($counters['edmToday'])) {
                $todayBlocks['edmToday'] = $counters['edmToday'];
            }

            if (isset($counters['swiftfinToday'])) {
                $todayBlocks['swiftfinToday'] = $counters['swiftfinToday'];
            }

            if (isset($counters['isoToday'])) {
                $todayBlocks['isoToday'] = $counters['isoToday'];
            }

            if (isset($counters['fileactToday'])) {
                $todayBlocks['fileactToday'] = $counters['fileactToday'];
            }

            if (isset($counters['finzipToday'])) {
                $todayBlocks['finzipToday'] = $counters['finzipToday'];
            }

            if (isset($counters['errorsToday'])) {
                $todayBlocks['errorsToday'] = $counters['errorsToday'];
            }

        ?>

        <?php
            if ($todayBlocks) {
                echo $this->render('_today', ['todayBlocks' => $todayBlocks]);
            }
        ?>
    </div>
    <div class="row">
        <?php
            // Шаблоны
            if (isset($counters['edmTemplates'])) {
                echo $this->render('_templates', [
                        'templates' => $counters['edmTemplates']
                    ]
                );
            }
        ?>
    </div>
</div>
