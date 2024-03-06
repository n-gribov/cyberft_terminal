<?php

use addons\swiftfin\models\documents\mt\MtUniversalDocument;
use kartik\widgets\ActiveForm;
use yii\web\View;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model MtUniversalDocument */
?>

<table class="table" style="margin: 0">
    <tr>
        <td width="10%"><?=$model->getNode('72')->attributeToHtmlForm('withdraw', $form)?></td>
        <td width="2%"></td>
        <td width="10%"><?=$model->getNode('72')->attributeToHtmlForm('enrolled', $form)?></td>
        <td></td>
    </tr>
</table>
<?php \yii\jui\DatePicker::widget([
    'id'         => 'mtuniversal103document-72-withdraw',
    'name'       => 'mtuniversal103document-72-withdraw',
    'dateFormat' => 'yyMMdd',
]) ?>

<?php \yii\jui\DatePicker::widget([
    'id'         => 'mtuniversal103document-72-enrolled',
    'name'       => 'mtuniversal103document-72-enrolled',
    'dateFormat' => 'yyMMdd',
]) ?>

<table class="table">
    <tr>
        <td width="240"><h4>Платежное поручение №</h4></td>
        <td width="10%"><?=$model->getNode('20')->attributeToHtmlForm('esNumber', $form)?></td>
        <td></td>
        <td width="10%"><?=$model->getNode('20')->attributeToHtmlForm('date', $form)?></td>
        <td width="2%"></td>
        <td width="10%"><?=$model->getNode('72')->attributeToHtmlForm('paymentType', $form)?></td>
        <td></td>
    </tr>
</table>
<?php \yii\jui\DatePicker::widget([
    'id'         => 'mtuniversal103document-20-date',
    'name'       => 'mtuniversal103document-20-date',
    'dateFormat' => 'yyMMdd',
]) ?>

<table class="table table-bordered" style="margin-bottom: -1px">
    <tr>
        <td><?=$model->getNode('50K')->attributeToHtmlForm('inn', $form)?></td>
        <td><?=$model->getNode('50K')->attributeToHtmlForm('kpp', $form)?></td>
        <td rowspan="2">Сумма</td>
        <td colspan="3" rowspan="2"><?=$model->getNode('32A')->attributeToHtmlForm('sum', $form)?></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2"><?=$model->getNode('50K')->attributeToHtmlForm('nomination', $form)?></td>
    </tr>
    <tr>
        <td>Сч. №</td>
        <td colspan="3"><?=$model->getNode('50K')->attributeToHtmlForm('accountNumber', $form)?></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2">Банк плательщика:<p id="payerBankName"></p></td>
        <td>БИК</td>
        <td colspan="3"><?=$model->getNode('52D')->attributeToHtmlForm('bik', $form)?></td>
    </tr>
    <tr>
        <td>Сч. №</td>
        <td colspan="3"><?=$model->getNode('52D')->attributeToHtmlForm('accountNumber', $form)?></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2">Банк получателя:</td>
        <td>БИК</td>
        <td colspan="3"><?=$model->getNode('57D')->attributeToHtmlForm('bik', $form)?></td>
    </tr>
    <tr>
        <td>Сч. №</td>
        <td colspan="3"><?=$model->getNode('57D')->attributeToHtmlForm('accountNumber', $form)?></td>
    </tr>
    <tr>
        <td><?=$model->getNode('59')->attributeToHtmlForm('inn', $form)?></td>
        <td><?=$model->getNode('59')->attributeToHtmlForm('kpp', $form)?></td>
        <td>Сч. №</td>
        <td colspan="3" rowspan="2"><?=$model->getNode('59')->attributeToHtmlForm('accountNumber', $form)?></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="4"><?=$model->getNode('59')->attributeToHtmlForm('nomination', $form)?></td>
    </tr>
    <tr>
        <td>Вид. оп.</td>
        <td><?=$model->getNode('72')->attributeToHtmlForm('operationType', $form)?></td>
        <td>Срок. плат.</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Наз. пл.</td>
        <td>&nbsp;</td>
        <td>Очер. плат.</td>
        <td><?=$model->getNode('72')->attributeToHtmlForm('paymentPriority', $form)?></td>
    </tr>
    <tr>
        <td width="100">Код</td>
        <td width="100">&nbsp;</td>
        <td width="100">Рез. поле</td>
        <td width="100">&nbsp;</td>
    </tr>
</table>
<table class="table table-bordered">
    <tr>
        <td><?=$model->getNode('77B')->attributeToHtmlForm('kbk', $form)?></td>
        <td><?=$model->getNode('77B')->attributeToHtmlForm('okato', $form)?></td>
        <td><?=$model->getNode('77B')->attributeToHtmlForm('paymentReason', $form)?></td>
        <td><?=$model->getNode('77B')->attributeToHtmlForm('taxablePeriod', $form)?></td>
        <td><?=$model->getNode('77B')->attributeToHtmlForm('taxableNumber', $form)?></td>
        <td><?=$model->getNode('77B')->attributeToHtmlForm('taxableDocDate', $form)?></td>
        <td><?=$model->getNode('77B')->attributeToHtmlForm('paymentType', $form)?></td>
    </tr>
</table>

<?php
/**
 * Приматываем синей изолентой
 */
$scheme = $model->getNode('50K')->getAttributeScheme('accountNumber');
?>
<?php if (
    isset($scheme['strict']) && !empty($scheme['strict'])
    && is_file(Yii::getAlias('@clientConfig/documents/mt/103PaymentOrder.json'))
) : ?>
    <script language="JavaScript" type="text/javascript">
        <?php ob_start() ?>
        $(window).ready(function () {
            $('#mtuniversal103document-52d-bik').attr('readonly', true);
            $('#mtuniversal103document-52d-accountnumber').attr('readonly', true);

            $('#mtuniversal103document-50k-accountnumber').change(function () {
                var attr = veles[$(this).val()];
                if (undefined !== attr) {
                    $('#mtuniversal103document-50k-nomination').val(attr.name);
                    $('#mtuniversal103document-52d-bik').val(attr.bik);
                    $('#mtuniversal103document-52d-accountnumber').val(attr.account);
                    $('#mtuniversal103document-50k-inn').val(attr.inn);
                    $('#mtuniversal103document-50k-kpp').val(attr.kpp);
                    $('#payerBankName').html(attr.bank);
                } else {
                    $('#mtuniversal103document-50k-nomination').val('');
                    $('#mtuniversal103document-52d-bik').val('');
                    $('#mtuniversal103document-52d-accountnumber').val('');
                    $('#mtuniversal103document-50k-inn').val('');
                    $('#mtuniversal103document-50k-kpp').val('');
                    $('#payerBankName').html('');
                }
            });
        });
        var veles = <?=file_get_contents(Yii::getAlias('@clientConfig/documents/mt/103PaymentOrder.json'))?>;
    <?php $js = ob_get_contents(); ob_end_clean(); ?>
</script>
<?php $this->registerJs($js) ?>
<?php endif ?>
