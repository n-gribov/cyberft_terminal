<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use addons\edm\models\DictContractor;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use kartik\select2\Select2;
use addons\edm\models\DictBank;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model DictContractor */
/* @var $form yii\widgets\ActiveForm */
if ($model->bankBik) {
    $bank = \addons\edm\models\DictBank::findOne(['bik' => $model->bankBik]);
}

if (!DictBank::find()->exists()) {
    $link = "<a href='".Url::to(['dict-bank/index'])."'>".Yii::t('edm', 'Load')."</a>";
    // Поместить в сессию флаг сообщения о ненайденном банке
    Yii::$app->session->addFlash('error', Yii::t('edm', 'The bank for the account is not available - bank directory is not loaded {link}', ['link' => $link]));
    $dictBankList = [];
} else if (DictBank::allTerminalIdIsNull()) {
    $link = "<a href='".Url::to(['dict-bank/index'])."'>".Yii::t('edm', 'Define codes')."</a>";
    // Поместить в сессию флаг сообщения об отсутствии кодов в справочнике банков
    Yii::$app->session->addFlash('error', Yii::t('edm', 'The bank cannot be used - bank directory does not contain participant codes {link}', ['link' => $link]));
    $dictBankList = [];
} else {
    $dictBank = DictBank::getDictBankListWithTerminalId();
    $dictBankList = [];
    foreach ($dictBank as $dictBankLine) {
        $dictBankList[$dictBankLine->bik] = Yii::t('edm', 'BIK') . ': ' . $dictBankLine->bik . ' ' . Yii::t('edm', 'Bank') . ': ' . $dictBankLine->name;
    }
}

// Получение списка валют для передачи в форму выбора
$currencies = ArrayHelper::map(DictCurrency::find()->all(), 'id', 'name');

// Получение списка организаций
$organizations = Yii::$app->terminalAccess->query(DictOrganization::className())->all();
$organizationsList = ArrayHelper::map($organizations, 'id', 'name');

?>

<?php $form = ActiveForm::begin(); ?>

<?=$form->field($model, 'name')->textInput(['maxlength' => true])?>

<?php
    /*
     *  (CYB-4440) В зависимости от того, подаем ли мы имя организации или нет,
     *  мы даем возможность выбора организации из списка или нет
     */
?>

<?= $form->field($model, 'organizationId')->widget(Select2::classname(), [
                'data' => isset($name) ? [array_search($name, $organizationsList) => $name] : $organizationsList,
                'options' => isset($name) ? [] : ['prompt' => ''],
                'hideSearch' => isset($name) ? true : false,
                'readonly' => isset($name) ? true : false,
                'pluginOptions' => isset($name) ? [] : ['allowClear' => true],
    ])
?>

<?= $form->field($model, 'number') ?>
<?php MaskedInput::widget([
    'model' => $model,
    'attribute' => 'number',
    'mask'          => '99999.999.9.99999999999',
    'clientOptions' => [
        'placeholder' => '_____.___._.___________',
    ]
])?>

<?=$form->field($model, 'currencyId')->dropDownList($currencies, ['prompt' => ''])?>

<?=$item = $form->field($model, 'bankBik')->widget(Select2::classname(), [
    'model' => $model,
    'attribute' => 'bankBik',
    'options'       => [
        'placeholder' => Yii::t('app', 'Search for a {label}', ['label' => Yii::t('edm', 'bank by name or BIK')]),
        'style'       => 'width:100%',
    ],
    'id' => 'edmdictcontractor-bankBik',
    'data' => $dictBankList,
    'pluginOptions' => [
        'allowClear'         => true,
        'minimumInputLength' => 0,
    ]
]);
?>

    <?=$form->field($model, 'requireSignQty')->dropDownList(EdmPayerAccount::$signaturesNumberOptions)?>

    <?=$form->field($model, 'payerName')->textInput(['maxlength' => true])?>

    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
    </div>

<?php ActiveForm::end(); ?>

<?php

// JS-скрипты для представления
$script = <<<JS

        // Получение валюты по номеру счета
        // при изменении номера счета
        $('#edmpayeraccount-number').on('keyup', function() {
            // Получаем текущее значение из поля номера счета
            var accountNumber = $(this).val();

            // Удаляем лишние символы из строки
            var accountRaw = accountNumber.replace(/[_.]/g, '');

            // Количество символов получившегося номера счета
            var accountLength = accountRaw.length;

            // Если у нас полный номер счета (20 символов),
            // пытаемся подобрать для него валюту
            if (accountLength !== 20) {
                return false;
            }

            // Получаем числовой код валюты из счета (символы 5-8)
            var currencyCode = accountRaw.substring(5,8);

            $.ajax({
                url: '/edm/dict-beneficiary-contractor/get-currency',
                type: 'get',
                data: 'code=' + currencyCode,
                success: function(res){

                    // Если валюта найдена,
                    // автоматически выбираем её в списке валют
                    if (res) {
                        $('#edmpayeraccount-currencyid').val(res);
                    } else {
                        // Если валюта не найдена, очищаем поле выбора
                        $('#edmpayeraccount-currencyid').val('');
                    }
                }
            });
        });

        deprecateSpaceSymbol('#edmpayeraccount-payername');
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);

?>