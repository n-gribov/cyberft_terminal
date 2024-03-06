<?php
/** @var \yii\web\View $this */

$organization = $typeModel->getOrganization();
$organizationAccountBank = $typeModel->getBank();
?>

<div class="container">
    <div class="row fcst-print-mb-20">
       <div class="col-sm-4 text-center">
           <div>Наименование предприятия</div>
           <div><strong><?=$organization->name?></strong></div>
       </div>
       <div class="col-sm-5 pull-right text-center">
           <div>Наименование уполномоченного банка</div>
           <div><strong><?=$organizationAccountBank->name?></strong></div>
       </div>
    </div>
    <div class="row text-center fcst-print-mb-20">
        <div>
            <strong>Поручение № <?=$typeModel->number?></strong>
        </div>
        <div>
            <strong>на обязательную продажу иностранной валюты</strong>
        </div>
    </div>
    <div class="row fcst-print-mb-20">
        <div class="col-sm-1">Дата</div>
        <div class="col-sm-3 fcst-print-bb"><?=\Yii::$app->formatter->asDateTime(time(), 'php:d F Y')?></div>
    </div>
    <div class="row fcst-print-mb-20">
        <div class="col-sm-4">Наименование предприятия</div>
        <div class="col-sm-8 fcst-print-bb"><?=$organization->name?></div>
    </div>
    <div class="row fcst-print-mb-20">
        <div class="col-sm-3">Почтовый адрес</div>
        <div class="col-sm-9 fcst-print-bb"><?=$organization->address?></div>
    </div>
    <div class="row fcst-print-mb-10">
        <div class="col-sm-12">Ф.И.О. сотрудника, уполномоченного на решение вопросов по сделке</div>
    </div>
    <div class="row fcst-print-mb-20 fcst-print-bb">
        <div class="col-sm-12"><?=$typeModel->contactPersonName?></div>
    </div>
    <div class="row fcst-print-mb-20">
        <div class="col-sm-4">Номер телефона</div>
        <div class="col-sm-8 fcst-print-bb"><?=$typeModel->contactPersonPhone?></div>
    </div>
    <div class="row fcst-print-mb-10">
        <div class="col-sm-6">Из общей выручки (сумма цифрами и прописью)</div>
        <div class="col-sm-6 fcst-print-bb"><?=Yii::$app->formatter->asDecimal($typeModel->amount, 2)?> <?=$typeModel->getAmountCurrency()?></div>
    </div>
    <div class="row text-center fcst-print-mb-20">
        <div class="col-sm-12 fcst-print-bb"><?=$typeModel->getAmountInWords()?></div>
    </div>
    <div class="row fcst-print-mb-20">
        <div class="col-sm-6">просим списать с нашего транзитного счета №</div>
        <div class="col-sm-6 fcst-print-bb text-center"><?=$typeModel->transitAccount?></div>
    </div>
    <div class="row fcst-print-mb-10">
        <div class="col-sm-6">с зачислением на текущий валютный счет №</div>
        <div class="col-sm-6 fcst-print-bb text-center"><?=$typeModel->foreignAccount?></div>
    </div>
    <div class="row fcst-print-mb-10">
        <div class="col-sm-4">(сумма цифрами и прописью)</div>
        <div class="col-sm-8 fcst-print-bb text-center"><?=Yii::$app->formatter->asDecimal($typeModel->amountTransfer, 2)?> <?=$typeModel->getAmountTransferCurrency()?></div>
    </div>
    <div class="row fcst-print-mb-20">
        <div class="col-sm-12 fcst-print-bb text-center"><?=$typeModel->getAmountTransferInWords()?></div>
    </div>
    <?php if ($typeModel->amountSell) : ?>
    <div class="row fcst-print-mb-10">
        <div class="col-sm-12">Продать на валютном рынке</div>
    </div>
    <div class="row fcst-print-mb-10">
        <div class="col-sm-4">(сумма цифрами и прописью)</div>
        <div class="col-sm-8 fcst-print-bb text-center"><?=Yii::$app->formatter->asDecimal($typeModel->amountSell, 2)?> <?=$typeModel->getAmountSellCurrency()?></div>
    </div>
    <div class="row fcst-print-mb-10">
        <div class="col-sm-12 fcst-print-bb text-center"><?=$typeModel->getAmountSellInWords()?></div>
    </div>
    <div class="row fcst-print-mb-20">
        <div class="col-sm-12 text-center">(по согласованному с вами курсу)</div>
    </div>
    <?php endif ?>
    <div class="row fcst-print-mb-50">
        <div class="col-sm-4">№ и дата уведомления на тр/сч</div>
        <div class="col-sm-8 fcst-print-bb text-center">
            <?=$typeModel->currencyIncomingNumber?> /
            <?=\Yii::$app->formatter->asDateTime(strtotime($typeModel->currencyIncomingDate), 'php:d F Y')?>
        </div>
    </div>
    <?php if ($typeModel->amountSell) : ?>
    <div class="row fcst-print-mb-10">
        <div class="col-md-12">Полученную от обязательной продажи сумму рублей просим перевести на счет №</div>
    </div>
    <div class="row fcst-print-mb-20">
        <div class="col-md-12 fcst-print-bb text-center"><?=$typeModel->account?></div>
    </div>
    <?php endif ?>
    <?php if (count($signatures) > 0): ?>
        <div class="row">
            <div class="col-md-5 pull-right">
                <?= // Вывести блок подписей
                    $this->render('@common/views/document/_signatures', ['signatures' => $signatures]) ?>
            </div>
        </div>
    <?php endif ?>
</div>
<style>
    .fcst-print-mb-50 {
        margin-bottom: 50px;
    }

    .fcst-print-mb-20 {
        margin-bottom: 20px;
    }

    .fcst-print-mb-10 {
        margin-bottom: 10px;
    }

    .fcst-print-bb {
        border-bottom: 1px solid #000;
    }

    @media print {
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left;
        }
        .col-sm-12 {
            width: 100%;
        }
        .col-sm-11 {
            width: 91.66666667%;
        }
        .col-sm-10 {
            width: 83.33333333%;
        }
        .col-sm-9 {
            width: 75%;
        }
        .col-sm-8 {
            width: 66.66666667%;
        }
        .col-sm-7 {
            width: 58.33333333%;
        }
        .col-sm-6 {
            width: 50%;
        }
        .col-sm-5 {
            width: 41.66666667%;
        }
        .col-sm-4 {
            width: 33.33333333%;
        }
        .col-sm-3 {
            width: 25%;
        }
        .col-sm-2 {
            width: 16.66666667%;
        }
        .col-sm-1 {
            width: 8.33333333%;
        }
    }
</style>