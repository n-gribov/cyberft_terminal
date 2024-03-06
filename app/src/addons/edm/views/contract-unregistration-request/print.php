<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm $model */
/** @var \addons\edm\models\VTBContractUnReg\VTBContractUnRegType $typeModel */

?>

<h1 class="text-center">
    Заявление о снятии контракта (кредитного договора) с учета
    <br>
    №<?= Html::encode($model->documentNumber) ?>
    от <?= Html::encode($model->documentDate) ?>
</h1>

<table class="full-width" style="margin-bottom: 30px">
    <tr>
        <td>Наименование уполномоченного банка</td>
        <td><strong><?= Html::encode($model->receiverBank->name) ?></strong></td>
    </tr>
    <tr>
        <td>Наименование организации</td>
        <td><strong><?= Html::encode($model->organization->name) ?></strong></td>
    </tr>
    <tr>
        <td>ИНН</td>
        <td><strong><?= Html::encode($model->organization->inn) ?></strong></td>
    </tr>
    <tr>
        <td>ФИО ответственного лица</td>
        <td><strong><?= Html::encode($model->contactPerson) ?></strong> Телефон <?= Html::encode($model->contactPhone) ?></td>
    </tr>
</table>

<p>Просим снять контракт(ы) (кредитные договор(ы)) с учета:</p>
<table class="bordered full-width">
    <tr>
        <th>Уникальный номер контракта (кредитного договора)</th>
        <th>Дата</th>
        <th>Пункт инструкции</th>
        <th>Основание для снятия с учета</th>
    </tr>
    <?php foreach ($model->contracts as $contract) : ?>
        <tr>
            <td><?= Html::encode($contract->uniqueContractNumber) ?></td>
            <td><?= Html::encode($contract->uniqueContractNumberDate) ?></td>
            <td><?= Html::encode($contract->unregistrationGroundCode) ?></td>
            <td><?= Html::encode($contract->unregistrationGroundName) ?></td>
        </tr>
    <?php endforeach ?>
</table>

<table id="signatures-table">
    <tr>
        <th style="width: 25%"></th>
        <th style="width: 25%"></th>
        <th>От клиента</th>
        <th></th>
    </tr>
    <?php foreach ($typeModel->signatureInfo->signatures as $signature) : ?>
        <tr>
            <td></td>
            <td style="border-bottom: 1px solid black;" class="text-center"><?= Html::encode($signature->uid) ?></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="4" class="text-right">(Дата подписания)</td>
        </tr>
    <?php endforeach ?>
</table>

<p>Информация уполномоченного банка</p>

<style>
    .bordered, table.bordered td, table.bordered th {
        border: 1px solid black;
    }
    td, th {
        min-height: 14px;
        min-width: 7px;
        padding: 5px;
    }
    th {
        font-weight: normal;
        text-align: center;
        vertical-align: top;
    }
    .full-width {
        width: 100%;
    }
    #forPrintPreview {
        font-size: 14px !important;
    }
    #signatures-table {
        margin-bottom: 60px;
        margin-top: 60px;
        width: 100%;
    }
    h1 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 30px;
    }
</style>
