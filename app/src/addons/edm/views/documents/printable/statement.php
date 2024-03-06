<?php

use addons\edm\models\Statement\StatementTypeConverter;
use common\document\Document;
use common\helpers\Html;
use yii\data\ArrayDataProvider;
use common\widgets\GridView;
use yii\grid\SerialColumn;
use common\models\cyberxml\CyberXmlDocument;
use addons\edm\models\EdmPayerAccount;
use common\helpers\DateHelper;
use yii\web\View;

/** @var $model Document */

if ($model->getValidStoredFileId()) {
    $typeModel = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
    $content = StatementTypeConverter::convertFrom($typeModel);
} else if ($model->status == $model::STATUS_CREATING) {
    echo 'Документ еще не создан';

    return;
} else {
    echo 'К сожалению, нет возможности отобразить документ данного типа';

    return;
}

$payerAccount = EdmPayerAccount::findOne(['number' => $content->statementAccountNumber]);

if (!$payerAccount) {
    echo 'Выписка по неизвестному счету';
    return;
}

$dataProvider = new ArrayDataProvider([
	'allModels' => $content->transactions,
	'pagination' => false
]);

$statementPeriodStart = new DateTime($content->statementPeriodStart);
$statementPeriodEnd = new DateTime($content->statementPeriodEnd);

?>

<div class="received-block">
    <?= Yii::t('app', 'Received by CyberFT') ?>
</div>

<table class="info-table">
    <tr>
        <td>Владелец счета</td>
        <td colspan="8"><strong><?=$payerAccount->getPayerName()?></strong></td>
    </tr>
    <tr>
        <td style="width: 115px;">
            Счет
        </td>
        <td style="width: 200px;">
            <strong><?=$content->statementAccountNumber?></strong>
        </td>
        <td style="width: 85px;"><strong>Остатки</strong></td>
        <td style="width: 110px;"></td>
        <td style="width: 70px;"><strong>Обороты</strong></td>
        <td></td>
    </tr>
    <tr>
        <td>Валюта счета</td>
        <td><strong><?=$payerAccount->edmDictCurrencies->name?></strong></td>
        <td>Входящий</td>
        <td>
            <strong>
                <?=number_format($content->openingBalance, 2, '.', " ")?>
            </strong>
        </td>
        <td>Дебет</td>
        <td style="width: 117px;">
            <strong>
                <?=number_format($content->debitTurnover, 2, '.', " ")?>
            </strong>
        </td>
        <td style="width: 181px;">Период выписки</td>
        <td>
            <strong>
                <?=$content->statementPeriodStart?> - <?=$content->statementPeriodEnd?>
            </strong>
        </td>
    </tr>
    <tr>
        <td>Банк</td>
        <td><strong><?= Html::encode($payerAccount->bank->name) ?></strong></td>
        <td>Исходящий</td>
        <td>
            <strong>
                <?=number_format($content->closingBalance, 2, '.', " ")?>
            </strong>
        </td>
        <td>Кредит</td>
        <td>
            <strong>
                <?=number_format($content->creditTurnover, 2, '.', " ")?>
            </strong>
        </td>
        <td>Дата последней операции</td>
        <td><strong><?= $content->prevLastOperationDate ? date('d.m.Y', strtotime($content->prevLastOperationDate)) : '' ?></strong></td>
    </tr>
</table>



<?php if ($dataProvider->totalCount): ?>
<?php
    // Создать таблицу для вывода
    echo GridView::widget([
        'emptyText' => '',
        'filterUrl' => false,
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => SerialColumn::className(),
                'contentOptions' => [
                    'style' => 'text-align: right',
                ],
            ],
            [
                'attribute' => 'ValueDate',
                'format'    => ['date', 'php:d.m.Y'],
                'label'     => Yii::t('doc/st', 'Date'),
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right; width: 48px;',
                    'nowrap' => 'nowrap'
                ],
                'optimizeCellLabel' => false,
                'value' => function($model) {
                    return DateHelper::formatDate($model['ValueDate'], 'date');
                },
            ],
            [
                'attribute'      => 'Number',
                'enableSorting'  => false,
                'label'          => Yii::t('doc/st', 'Number'),
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right; width: 48px;',
                    'nowrap' => 'nowrap'
                ],
                'optimizeCellLabel' => false
            ],
            [
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('doc/st', 'Correspondent'),
                    'value' => function($model) {
                        $accountNumber = $model['PayerAccountNum'];
                        $accountOwnerName = $model['PayerName'];
                        return $accountNumber. '<br/>' .$accountOwnerName
                            . '<br/>' . Yii::t('doc/st', 'INN') . ': ' . $model['PayerINN']
                            . '<br/>' . Yii::t('doc/st', 'KPP') . ': ' . $model['PayerKPP'];
                    }
            ],
            [
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('doc/st', 'Corr. Bank'),
                'value' => function($model) {
                    $bik = $model['PayerBIK'];
                    $bankName = $model['PayerBankName'];
                    return Yii::t('edm', 'BIK') . ": $bik<br>$bankName";
                },
                'optimizeCellLabel' => false
            ],
            [
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('doc/st', 'Payee'),
                'value' => function($model) {
                    $accountNumber = $model['PayeeAccountNum'];
                    $accountOwnerName = $model['PayeeName'];
                    return $accountNumber. '<br/>' .$accountOwnerName
                            . '<br/>' . Yii::t('doc/st', 'INN') . ': ' . $model['PayeeINN']
                            . '<br/>' . Yii::t('doc/st', 'KPP') . ': ' . $model['PayeeKPP'];
                }
            ],
            [
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('doc/st', 'Payee Bank'),
                'value' => function($model) {
                    $bik = $model['PayeeBIK'];
                    $bankName = $model['PayeeBankName'];
                    return Yii::t('edm', 'BIK') . ": $bik<br>$bankName";
                },
                'optimizeCellLabel' => false
                
            ],
            [
                'attribute'      => 'Debit',
                'enableSorting'  => false,
                'label'          => Yii::t('doc/st', 'Debit'),
                'value' => function($row) {
                    return Yii::$app->formatter->asDecimal($row['Debit'], 2);
                },
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right',
                    'nowrap' => 'nowrap'
                ],
            ],
            [
                'attribute'      => 'Credit',
                'enableSorting'  => false,
                'label'          => Yii::t('doc/st', 'Credit'),
                'value' => function($row) {
                    return Yii::$app->formatter->asDecimal($row['Credit'], 2);
                },
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right',
                    'nowrap' => 'nowrap'
                ],
            ],
            [
                'attribute'     => 'Purpose',
                'enableSorting' => false,
                'label'         => Yii::t('doc/st', 'Purpose'),
                'optimizeCellLabel' => false
            ],
        ],
    ]);
?>

<?php endif?>

<?php

$cssFile = Yii::getAlias('@backend/web/css/edm/statement/style.css');
$cssContent = file_get_contents($cssFile);
$this->registerCss($cssContent);
