<?php

use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\Statement\StatementTypeConverter;
use common\models\cyberxml\CyberXmlDocument;

/** @var \common\document\Document $model */
/** @var \addons\edm\models\Statement\StatementType $content */

if ($model->getValidStoredFileId()) {
    $typeModel = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
    $content = StatementTypeConverter::convertFrom($typeModel);
} else if ($model->status == $model::STATUS_CREATING) {
    echo 'Документ еще не создан';
    return;
} else {
    echo 'Нет возможности отобразить документ данного типа';
    return;
}

$statementPeriodStart = new DateTime($content->statementPeriodStart);
$statementPeriodEnd = new DateTime($content->statementPeriodEnd);

$paymentOrders = array_map(
    function ($transactionIndex) use ($content) {
        return $content->getPaymentOrder($transactionIndex);
    },
    array_keys($content->transactions)
);

usort(
    $paymentOrders,
    function (PaymentOrderType $a, PaymentOrderType $b) use ($content) {
        $aIsDebit = $a->payerCheckingAccount === $content->statementAccountNumber;
        $bIsDebit = $b->payerCheckingAccount === $content->statementAccountNumber;
        if ($aIsDebit === $bIsDebit) {
            return $a->sum - $b->sum;
        }
        return $bIsDebit - $aIsDebit;
    }
);

foreach ($paymentOrders as $index => $paymentOrder) {
    if ($index > 0) {
        echo '<p class="breakhere">';
    }

    // Вывести страницу
    echo $this->render('paymentOrder', ['paymentOrder' => $paymentOrder, 'savePdf' => isset($savePdf)]);

    if ($index > 0) {
        echo '</p>';
    }
}

$this->registerCss(<<<CSS
    p.breakhere {
        page-break-after: always;
        clear:both
    }
    .static-header {
        display: block;
        width: 100%;
    }
CSS);
