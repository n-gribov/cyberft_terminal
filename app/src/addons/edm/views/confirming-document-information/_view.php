<?php

/** @var \yii\web\View $this */
/** @var \addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt $model */
/** @var \common\models\listitem\AttachedFile[][] $attachedFiles */
/** @var array $signatures */

use yii\helpers\Html;

Yii::$app->formatter->nullDisplay = '';
?>

<div class="row">
    <div class="col-sm-12 text-right margin-bottom-5">
        Код формы по ОКУД 0406010
    </div>
</div>

<table id="header-table" class="table table-bordered">
    <tr>
        <td>Наименование банка УК</td>
        <td><?= Html::encode($model->organizationBankName) ?></td>
    </tr>
    <tr>
        <td>Наименование резидента</td>
        <td><?= Html::encode($model->organizationName) ?></td>
    </tr>
</table>

<h4 class="color-black text-center text-uppercase">Справка о подтверждающих документах</h4>
<h5 class="color-black text-center">от <?=$model->date?></h5>
<h4 class="color-black text-center">Уникальный номер контракта (кредитного договора) <?=$model->contractPassport?></h4>

<div class="row">
    <div class="col-sm-12">
        <table id="documents-table" class="table table-bordered">
            <thead>
            <tr>
                <th rowspan="3">№ <span class="nowrap">п/п</span></th>
                <th colspan="2" rowspan="2">Подтверждающий документ</th>
                <th rowspan="3">Код вида подтверждающего документа</th>
                <th colspan="4">Сумма по подтверждающему документу</th>
                <th rowspan="3">Признак поставки</th>
                <th rowspan="3">Ожидаемый срок репатриации иностранной валюты и (или) валюта Российской Федерации</th>
                <th rowspan="3">Код страны грузоотправителя (грузополучателя)</th>
                <th rowspan="3">Признак корректировки</th>
            </tr>
            <tr>
                <th colspan="2" rowspan="">в единицах валюты документа</th>
                <th colspan="2" rowspan="">в единицах валюты контракта (кредитного договора)</th>
            </tr>
            <tr>
                <th>№</th>
                <th>дата</th>
                <th>код валюты</th>
                <th>сумма</th>
                <th>код валюты</th>
                <th>сумма</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
                <th>8</th>
                <th>9</th>
                <th>10</th>
                <th>11</th>
                <th>12</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model->items as $index => $document) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?=$document->number?></td>
                    <td><?=$document->date?></td>
                    <td><?=$document->code?></td>
                    <td><?=$document->currencyDocumentCode?></td>
                    <td><?=Yii::$app->formatter->asDecimal($document->sumDocument, 2)?></td>
                    <td><?=$document->currencyContractCode?></td>
                    <td><?=Yii::$app->formatter->asDecimal($document->sumContract, 2)?></td>
                    <td><?=$document->type?></td>
                    <td><?=$document->expectedDate?></td>
                    <td><?= $document->numericCountryCode ?></td>
                    <td><?= $document->originalDocumentDate ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <h4 class="color-black">Примечание</h4>
    </div>
</div>
<div class="row margin-bottom-25">
    <div class="col-sm-12">
        <table id="comments-table" class="table table-bordered">
            <thead>
            <tr>
                <th>№ строки</th>
                <th>Содержание</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->items as $index => $document) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $document->comment ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($attachedFiles && array_merge(...$attachedFiles)) : ?>
    <div class="row">
        <div class="col-sm-12">
            <h4 class="color-black"><?= Yii::t('edm', 'Attached documents') ?></h4>
        </div>
    </div>
    <div class="row margin-bottom-25">
        <div class="col-sm-12">
            <ul>
                <?php foreach (array_keys($model->items) as $itemIndex) : ?>
                    <?php foreach ($attachedFiles[$itemIndex] as $fileIndex => $attachedFile) : ?>
                        <li>
                            <?= Html::a(
                                $attachedFile->name,
                                [
                                    '/edm/confirming-document-information/download-attachment',
                                    'id' => $model->documentId,
                                    'itemIndex' => $itemIndex,
                                    'attachmentIndex' => $fileIndex,
                                ]
                            ) ?>
                        </li>
                    <?php endforeach ?>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
<?php endif ?>

<?php if ($signatures) : ?>
    <div class="row">
        <div class="col-sm-12">
            <?= // Вывести блок подписей
                $this->render('@common/views/document/_signatures', ['signatures' => $signatures]) ?>
        </div>
    </div>
<?php endif ?>

<?php

$this->registerCss('
    .nowrap {
        white-space: nowrap;
    }

    .table {
        width: 100%;
    }
    
    .table>thead>tr>th {
        text-align: center;
        vertical-align: top;
    }

    .table td {
        border: 1px solid #000;
        padding: 5px;
    }
    
    .table thead td {
        text-align: center;
    }
    
    .table-bordered>thead>tr>th, .table-bordered>thead>tr>td {
        border-bottom-width: 1px;
    }
    
    #header-table td:first-child {
        border-bottom-color: transparent !important;
        border-left-color: transparent !important;
        border-top-color: transparent !important;
        padding-left: 0px;
        width: 200px;
    }
    
    #documents-table td {
        text-align: center;
    }    
    
    #documents-table tbody td:not(:first-child) {
        width: 8.7%;
    }
    
    #comments-table td:first-child {
        text-align: center;
        width: 10%;
    }

    .margin-bottom-25 {
        margin-bottom: 25px;
    }

    .margin-bottom-5 {
        margin-bottom: 5px;
    }

    .color-black {
        color: #000;
    }

    @media print {
        @page {
            size: landscape;
        }
        [class*="col-sm-"] {
            float: left;
        }
        .table-bordered td, .table-bordered th {
            border-color: black !important;
        }
        .static-header {
            padding-top: 0;
        }
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
');
