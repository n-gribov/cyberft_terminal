<?php

use kartik\widgets\Select2;
use yii\helpers\Html;

$senderSelect = $data['senderSelect'];
$currencySelect = $data['currencySelect'];
?>


<?= Html::beginForm('', 'get') ?>

<div class="form-group col-lg-5">
    <?=
    Select2::widget([
        'name' => 'sender',
        'data' => $senderSelect,
    ]);
    ?>
</div>
<div class="form-group col-lg-1">
    <?=
    Select2::widget([
        'name' => 'currency',
        'data' => $currencySelect,
    ]);
    ?>
</div>
<input type="submit" value="<?=Yii::t('app', 'Next')?>" class="btn btn-success"/>

<?= Html::endForm() ?>
