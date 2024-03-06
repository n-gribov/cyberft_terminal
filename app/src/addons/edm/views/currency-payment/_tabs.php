<?php

use common\widgets\tabs\Tabs;

?>

<?= Tabs::widget([
    'items' => [
        [
            'title' => Yii::t('edm', 'Payment registers'),
            'url' => '/edm/currency-payment/register-index',
        ],
        [
            'title' => Yii::t('edm', 'Payment orders'),
            'url' => '/edm/currency-payment/payment-index',
        ],
    ]
])
?>
