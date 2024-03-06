<?php

use addons\edm\models\EdmPayerAccount;
use common\helpers\NumericHelper;

$organization = $typeModel->getOrganization();

Yii::$app->formatter->decimalSeparator = '.';

if ($typeModel->debitAmount) {
    $debitAccount = EdmPayerAccount::findOne(['number' => $typeModel->debitAccount]);
    $debitAccountCurrencyName = $debitAccount->edmDictCurrencies->name;

    $amount = $typeModel->debitAmount;
    $currencyName = $debitAccountCurrencyName;
} else {
    $creditAccount = EdmPayerAccount::findOne(['number' => $typeModel->creditAccount]);
    $creditAccountCurrencyName = $creditAccount->edmDictCurrencies->name;

    $amount = $typeModel->creditAmount;
    $currencyName = $creditAccountCurrencyName;
}

$amountFormatted = /*Yii::$app->formatter->asDecimal(*/$amount/*, 2)*/;
$amountInWords = NumericHelper::num2str($amount, $currencyName);

?>

<div class="container">
    <div class="row fcvn-print-mb-50">
        <div class="col-sm-4 text-center">
            <div>Наименование предприятия</div>
            <div><strong><?=$organization->name?></strong></div>
        </div>
        <div class="col-sm-5 pull-right text-center">
            <div>Наименование уполномоченного банка</div>
            <div><strong><?=$typeModel->getDebitAccountBankName()?></strong></div>
        </div>
    </div>
    <div class="row text-center fcvn-print-mb-50">
        <div>
            <strong>Поручение № <?=$typeModel->number?></strong>
        </div>
        <div>
            <strong>на конверсию валюты</strong>
        </div>
    </div>
    <div class="row fcvn-print-mb-20">
        <div class="col-sm-1">Дата</div>
        <div class="col-sm-3 fcvn-print-bb"><?=Yii::$app->formatter->asDateTime(time(), 'php:d F Y')?></div>
    </div>
    <div class="row fcvn-print-mb-20">
        <div class="col-sm-4">Наименование предприятия</div>
        <div class="col-sm-8 fcvn-print-bb"><?=$organization->name?></div>
    </div>
    <div class="row fcvn-print-mb-20">
        <div class="col-sm-3">Почтовый адрес</div>
        <div class="col-sm-9 fcvn-print-bb"><?=$organization->address?></div>
    </div>
    <div class="row fcvn-print-mb-10">
        <div class="col-sm-12">Ф.И.О. сотрудника, уполномоченного на решение вопросов по сделке</div>
    </div>
    <div class="row fcvn-print-mb-20 fcvn-print-bb">
        <div class="col-sm-12"><?=$typeModel->contactPersonName?></div>
    </div>
    <div class="row fcvn-print-mb-20">
        <div class="col-sm-3">Номер телефона</div>
        <div class="col-sm-9 fcvn-print-bb"><?=$typeModel->contactPersonPhone?></div>
    </div>
    <div class="row fcvn-print-mb-20">
        <div class="col-sm-6">Просим списать с нашего валютного счета №</div>
        <div class="col-sm-6 fcvn-print-bb"><?=$typeModel->debitAccount?></div>
    </div>
    <div class="row fcvn-print-mb-20">
        <div class="col-sm-5">с зачислением на валютный счет №</div>
        <div class="col-sm-7 fcvn-print-bb"><?=$typeModel->creditAccount?></div>
    </div>
    <div class="row fcvn-print-mb-20">
        <div class="col-sm-4">(сумма цифрами и прописью)</div>
        <div class="col-sm-8 fcvn-print-bb text-center"><?=$amountFormatted?> <?=$currencyName?></div>
    </div>
    <div class="row fcvn-print-mb-50">
        <div class="col-sm-12 fcvn-print-bb text-center"><?=$amountInWords?></div>
    </div>
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
    .fcvn-print-mb-50 {
        margin-bottom: 50px;
    }

    .fcvn-print-mb-20 {
        margin-bottom: 20px;
    }

    .fcvn-print-mb-10 {
        margin-bottom: 10px;
    }

    .fcvn-print-bb {
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