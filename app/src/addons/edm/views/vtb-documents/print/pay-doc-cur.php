<?php

use yii\helpers\Html;

/** @var \addons\edm\models\BaseVTBDocument\BaseVTBDocumentType $typeModel */
/** @var \addons\edm\models\DictOrganization $senderOrganization */
/** @var \common\document\DocumentStatusReportsData $statusReportsData */

/** @var \common\models\vtbxml\documents\PayDocCur $bsDocument */
$bsDocument = $typeModel->document;

$dateFormat = 'php:d.m.Y';
$dateTimeFormat = 'php:d.m.Y H:i';

$join = function ($glue, $values) {
    $nonEmptyValues = array_filter($values, function ($value) {
        return $value !== null && $value !== '';
    });
    $encodedValues = array_map('yii\helpers\Html::encode', $nonEmptyValues);
    return implode($glue, $encodedValues);
};
?>
<h1>Заявление</h1>
<p><strong>на перевод №</strong> <?= $bsDocument->DOCUMENTNUMBER ?></p>
<p><strong>от</strong> <?= Yii::$app->formatter->asDate($bsDocument->DOCUMENTDATE, $dateFormat) ?> г.</p>

<table id="document-table" class="bordered">
    <tr>
        <td>
            В случае необходимости просим связаться по телефону<br>
            (If required please call on):<br>
            <?= Html::encode($bsDocument->OFFICIALSPHONE) ?><br>
        </td>
        <td colspan="2">
            Сумму перевода просим списать с нашего счета у Вас (Please debit our account with you):<br>
            <?= Html::encode($bsDocument->PAYERACCOUNT) ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="block-label">Валюта</span>
            <span class="block-comment">Currency Code</span>
        </td>
        <td rowspan="2" class="swift-id-cell">32</td>
        <td><?= Html::encode($bsDocument->CURRCODETRANSFER) ?></td>
    </tr>
    <tr>
        <td>
            <span class="block-label">Сумма в иностранной валюте</span>
            <span class="block-comment">(цифрами и прописью)</span>
            <span class="block-comment">Amount in foreign currency</span>
            <span class="block-comment">(In figures and in writing)</span>
        </td>
        <td><?= Html::encode($bsDocument->AMOUNTTRANSFER) ?></td>
    </tr>
    <tr>
        <td>
            <span class="block-label">Перевододатель*</span>
            <span class="block-comment">(Наименование, адрес, город, страна, ИНН, КПП, ОКПО и/или ОГРН)</span>
            <span class="block-comment">Originator</span>
            <span class="block-comment">(Name, address, city, country)</span>
        </td>
        <td class="swift-id-cell">50</td>
        <td>
            <?= Html::encode($senderOrganization->nameLatin) ?><br>
            <?= $join(', ', [$senderOrganization->locationLatin, $senderOrganization->addressLatin]) ?><br>
        </td>
    </tr>
    <tr>
        <td>
            <span class="block-label">Номер счета бенефициара, IBAN</span>
            <span class="block-comment">Beneficiary account number, IBAN</span>
        </td>
        <td rowspan="2" class="swift-id-cell">59</td>
        <td><?= Html::encode($bsDocument->BENEFICIARACCOUNT) ?></td>
    </tr>
    <tr>
        <td>
            <span class="block-label">Бенефициар*</span>
            <span class="block-comment">(Наименование, адрес, город, страна)</span>
            <span class="block-comment">Beneficiary</span>
            <span class="block-comment">(Name, address, city, country)</span>
        </td>
        <td>
            <?php
                $beneficiaryCountry = \common\models\Country::findOneByCode($bsDocument->BENEFICIARCOUNTRYCODE);
                echo $join(
                    '<br>',
                    [
                        $bsDocument->BENEFICIAR,
                        $bsDocument->BENEFBANKADDRESS,
                        $beneficiaryCountry ? $beneficiaryCountry->nameInEnglish : null,
                    ]
                );
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="block-label">Банк бенефициара*</span>
            <span class="block-comment">(SWIFT, национальный клиринговый код, наименование, город, страна)</span>
            <span class="block-comment">Beneficiary’s bank</span>
            <span class="block-comment">(SWIFT, national clearing code, name, city, country)</span>
        </td>
        <td class="swift-id-cell">57</td>
        <td>
            <?php
            $beneficiaryBankCountry = \common\models\Country::findOneByCode($bsDocument->BENEFBANKCOUNTRYCODE);
            echo $join(
                '<br>',
                [
                    $bsDocument->BENEFBANKNAME,
                    $bsDocument->BENEFBANKBIC,
                    $bsDocument->BENEFBANKADDRESS,
                    $beneficiaryBankCountry ? $beneficiaryBankCountry->nameInEnglish : null,
                    $bsDocument->BENEFBANKPLACE,
                    $bsDocument->BENEFBANKACCOUNT,
                ]
            );
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="block-label">Банк-посредник**</span>
            <span class="block-comment">(SWIFT, национальный клиринговый код, наименование, страна, город)</span>
            <span class="block-comment">Intermediary Bank</span>
            <span class="block-comment">(SWIFT, national clearing code, name, city, country)</span>
        </td>
        <td class="swift-id-cell">56</td>
        <td>
            <?php
            $intermediaryBankCountry = \common\models\Country::findOneByCode($bsDocument->IMEDIABANKCOUNTRYCODE);
            echo $join(
                '<br>',
                [
                    $bsDocument->IMEDIABANKACCOUNT,
                    $bsDocument->IMEDIABANKADDRESS,
                    $bsDocument->IMEDIABANKBIC,
                    $intermediaryBankCountry ? $intermediaryBankCountry->nameInEnglish : null,
                    $bsDocument->IMEDIABANKNAME,
                    $bsDocument->IMEDIABANKPLACE,
                ]
            );
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="block-label">Назначение платежа*</span>
            <span class="block-comment">Details of payment</span>
        </td>
        <td class="swift-id-cell">70</td>
        <td><?= Html::encode($bsDocument->MESSAGEFORBANK) ?></td>
    </tr>
    <tr>
        <td colspan="3" id="ground-documents-cell">
            <table class="bordered">
                <tr>
                    <th>Вид обосновывающего документа</th>
                    <th>Номер документа</th>
                    <th>Дата документа</th>
                </tr>
                <?php foreach ($bsDocument->GROUNDDOCUMENTS as $groundDocument) : ?>
                    <tr>
                        <td><?= Html::encode($groundDocument->DOCUMENTTYPE) ?></td>
                        <td><?= Html::encode($groundDocument->DOCUMENTNUMBER) ?></td>
                        <td><?= Yii::$app->formatter->asDate($groundDocument->DOCUMENTDATE, $dateFormat) ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <span class="block-label">Дополнительная информация</span>
        </td>
        <td colspan="2">
            <?= Html::encode($bsDocument->ADDINFOVALCONTROL) ?>
        </td>
    </tr>
</table>

<p><strong>Расходы и комиссии по переводу</strong> (Bank charges and commissions):</p>

<div id="charges-block">
    <div>
        <span class="check-field <?= $bsDocument->CHARGESTYPE === 'OUR' ? 'checked' : '' ?>"></span>
        (OUR) списать с нашего счета в ПАО Банк ВТБ <span class="account"><?= Html::encode($bsDocument->CHARGESACCOUNT) ?></span><br>
        (при осуществлении переводов в USD в банки, расположенные на территории США; переводы в других иностранных валютах)
    </div>
    <div>
        <span class="check-field <?= $bsDocument->CHARGESTYPE === 'SHA' ? 'checked' : '' ?>"></span>
        (SHA) комиссии ПАО Банк ВТБ  списать с нашего <span class="account"><?= Html::encode($bsDocument->CHARGESACCOUNT) ?></span><br>
        счета, комиссии иностранных банков за счет бенефициара
    </div>
    <div>
        <span class="check-field <?= $bsDocument->CHARGESTYPE === 'BEN' ? 'checked' : '' ?>"></span>
        (BEN) все комиссии и расходы за счет бенефициара
    </div>
    <div>
        <span class="check-field <?= $bsDocument->CONVCHARGEACCOUNT ? 'checked' : '' ?>"></span>
        Дополнительную комиссию списать с нашего счета <span class="account"><?= Html::encode($bsDocument->CONVCHARGEACCOUNT) ?></span><br>
    </div>
</div>

<?= // Вывести колонтитул
    $this->render(
    '_bottom',
    [
        'typeModel' => $typeModel,
        'bankName' => $bsDocument->PAYERBANKNAME,
        'statusReportsData' => $statusReportsData,
        'stampStatus' => 'ACSC',
    ]
) ?>

<style>
    body {
        padding: 0;
    }
    td, th {
        padding: 3px 5px;
        vertical-align: top;
    }
    th {
        font-weight: normal;
        text-align: center;
    }
    table {
        margin: 0;
        width: 100%;
    }
    table.bordered th,
    table.bordered td {
        border: 1px solid black;
    }
    h1 {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 4px;
        margin-top: 0;
    }
    p {
        font-size: 14px;
        margin-bottom: 2px;
    }
    #ground-documents-cell {
        padding: 0;
    }
    #ground-documents-cell table th {
        width: 33%;
    }
    #ground-documents-cell table tr:first-child th {
        border-top: none;
    }
    #ground-documents-cell table tr:last-child td {
        border-bottom: none;
    }
    #ground-documents-cell table tr > :last-child {
        border-right: none;
    }
    #ground-documents-cell table tr > :first-child {
        border-left: none;
    }
    #ground-documents-cell th {
        font-weight: bold;
        font-style: italic;
        text-align: center;
    }
    #document-table .block-label {
        font-style: italic;
        font-weight: bold;
    }
    #document-table .block-comment {
        display: block;
    }
    .swift-id-cell {
        width: 30px;
        vertical-align: middle;
    }
    #charges-block {
        border: 1px solid black;
        padding: 5px 5px 5px 25px;
    }
    #charges-block .check-field {
        border: 1px solid black;
        margin-left: -20px;
        margin-right: 2px;
        height: 17px;
        width: 17px;
        display: inline-block;
        text-align: center;
        position: absolute;
    }
    #charges-block .check-field:after {
    }
    #charges-block .check-field.checked:after {
        content: 'X';
    }
    #charges-block .account {
        font-weight: bold;
        left: 320px;
        position: absolute;
    }
</style>
