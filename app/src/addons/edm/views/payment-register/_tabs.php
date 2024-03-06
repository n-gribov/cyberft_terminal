<?php

use common\widgets\tabs\Tabs;

?>

<div class='form-control-static'>
<?= Tabs::widget([
    'items' => [
        [
            'title' => Yii::t('edm', 'Payment registers'),
            'url' => '/edm/payment-register/index',
        ],
        [
            'title' => Yii::t('edm', 'Payment orders'),
            'url' => '/edm/payment-register/payment-order',
        ],
    ]
])
?>
</div>
