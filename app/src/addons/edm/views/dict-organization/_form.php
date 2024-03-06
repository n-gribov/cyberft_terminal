<?php

/* @var \addons\edm\models\DictOrganization $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use addons\edm\models\DictOrganization;
use common\models\Terminal;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\jui\DatePicker;
use addons\edm\helpers\EdmHelper;

// Получаем список id терминалов, для которых уже привязаны организации
// Если для текущей модели указан терминал,
// его не нужно включать в список занятых терминалов
$terminalsOrganizations = DictOrganization::find()
                            ->select('terminalId')
                            ->andFilterWhere(['!=', 'terminalId', $model->terminalId]);


$terminalsOrganizations = $terminalsOrganizations->asArray()->all();

// Преобразовываем список в нужный вид
$terminalsOrganizations = ArrayHelper::getColumn($terminalsOrganizations, 'terminalId');

// Получение списка терминалов, для которых еще не создана организация, с наименованиями из справочника
$terminals = Terminal::find()
            ->where(['status' => Terminal::STATUS_ACTIVE])
            ->andWhere(['not in', 'id', $terminalsOrganizations])
            ->all();

$terminalsSelect = [];

foreach($terminals as $terminal) {
    // Если у терминала есть наименование
    // организации, подставляем его
    // Иначе подставляем terminalId
    $terminalsSelect[$terminal->id] = empty($terminal->title)
                                        ? $terminal->terminalId
                                        : $terminal->terminalId . ' (' . $terminal->title . ')';
}

// Субьекты РФ
$regionsData = EdmHelper::fccRegions();

$regions = [];

foreach($regionsData as $value) {
    $regions[$value] = $value;
}

?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'terminalId')->widget(Select2::classname(), [
    'data' => $terminalsSelect,
    'options' => ['prompt' => ''],
    'pluginOptions' => [
        'allowClear' => true,
    ],
]); ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'type')->dropDownList(DictOrganization::typeValues()) ?>
<?= $form->field($model, 'propertyTypeCode')->dropDownList(DictOrganization::propertyTypeValues(), ['prompt' => '']) ?>
<?= $form->field($model, 'inn')->textInput(['maxlength' => '12']) ?>
<?php MaskedInput::widget([
    'model' => $model,
    'attribute' => 'inn',
    'mask' => $model->isNewRecord || $model->type === 'ENT' ? '9999999999' : '999999999999',
])?>
<?= $form->field(
    $model,
    'kpp',
    ['options' => ['class' => $model->type === 'IND' ? 'form-group hidden' : 'form-group']]
)->textInput(['maxlength' => true]) ?>

<?=$form->field($model, 'ogrn')->textInput(['maxlength' => true])?>

<div>
    <?=$form->field($model, 'dateEgrul')->textInput(['maxlength' => true])?>
    <?php MaskedInput::widget([
        'id'            => 'dictorganization-dateegrul',
        'name'          => 'dictorganization-dateegrul',
        'mask'          => '99.99.9999',
        'clientOptions' => [
            'placeholder' => 'dd.MM.yyyy',
        ]
    ])?>
    <?php DatePicker::widget([
        'id'         => 'dictorganization-dateegrul',
        'dateFormat' => 'dd.MM.yyyy',
    ]) ?>
</div>

<h4><?= Yii::t('edm', 'Address') ?></h4>
<hr>
<?=$form->field($model, 'address')->textInput(['readonly' => true, 'class' => 'address-field'])->label(false)?>
<hr>

<div class="set-address-block">
    <div class="row">
        <div class="col-md-3">
            <?=$form->field($model, 'state')->widget(Select2::classname(), [
                'data' => $regions,
                'options' => ['prompt' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ]])?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'city')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'district')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'locality')->textInput(['maxlength' => true])?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?=$form->field($model, 'street')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'buildingNumber')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'building')->textInput(['maxlength' => true])?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'apartment')->textInput(['maxlength' => true])?>
        </div>
    </div>
</div>

<h4><?= Yii::t('edm', 'Details in latin') ?></h4>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'nameLatin')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'locationLatin')->textInput(['maxlength' => true])?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'addressLatin')->textInput(['maxlength' => true])?>
    </div>
</div>

<h4><?=Yii::t('edm', 'Advanced options')?></h4>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger">
            <?=Yii::t('edm', 'Attention! It is not recommended to disable the validation of details because it can lead to errors in the processing of documents in the Bank!')?>
        </div>
        <?= $form->field($model, 'disablePayeeDetailsValidation')->checkbox() ?>
    </div>
</div>

<div class="form-group">
    <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ])?>
</div>

<?php ActiveForm::end(); ?>

<?php

// CSS для представления
$this->registerCss('
    .address-field {
        display: block;
        width: 100%;
        border: 0;
        font-weight: 700;
        font-size: 18px;
    }
');

// JS для представления
$script = <<< JS
    // Событие выбора терминала из списка
    $('#dictorganization-terminalid').on('change', function() {
        getTerminalTitle();
    });

    // Событие выбора типа юридического лица из списка
    $('#dictorganization-type').on('change', function() {
        // Очищаем текущее значение поля ИНН
        $('#dictorganization-inn').val('');

        // Меняем маску в зависимости от тип организации
        var type = $(this).find(":selected").val();

        if (type === 'ENT') {
            // Юр. лицо
            $('#dictorganization-inn').inputmask('9999999999');
            toggleKppField(true);
        } else if (type === 'IND') {
            // Физ. лицо
            $('#dictorganization-inn').inputmask('999999999999');
            toggleKppField(false);
        }
    });

    function toggleKppField(isEnabled) {
        var input = $('#dictorganization-kpp');
        input.closest('.form-group').toggleClass('hidden', !isEnabled);

        if (isEnabled) {
            if (input.data('prev-value')) {
                input.val(input.data('prev-value'));
            }
        } else {
            input.data('prev-value', input.val());
            input.val('');
        }
    }

    //;

    // Функция получения списка доступных типов ключей для терминала
    function getTerminalTitle() {
        // Очищаем текущее наименование организации
        $('#dictorganization-name').val('');

        // Получение текущего выбранного пункта списка
        var terminal = $('#dictorganization-terminalid').find(':selected').val();

        //Ajax-запрос для получения имени терминала по его id
        $.ajax({
            url: '/edm/dict-organization/get-terminal-title',
            type: 'get',
            data: 'id=' + terminal,
            success: function(res){
                // Записываем пришедшее значение в поле наименования
                $('#dictorganization-name').val(res);
            }
        });
    }

    function checkRegExp(e) {
        // спец. сочетания - не обрабатываем
        if (e.ctrlKey || e.altKey || e.metaKey) {
            return true;
        }

        var reg = new RegExp('^[а-я]+$');
        var char = String.fromCharCode(e.keyCode || e.charCode);

        if (char.match(reg)) {
            e.preventDefault();
        }
    }

    // Формирование адрес из составляющих элементов
    function generateAddress() {
       var fullAddress = '';

       var state = $('#dictorganization-state').val();
       var city = $('#dictorganization-city').val();
       var district = $('#dictorganization-district').val();
       var locality = $('#dictorganization-locality').val();
       var street = $('#dictorganization-street').val();
       var buildingNumber = $('#dictorganization-buildingnumber').val();
       var building = $('#dictorganization-building').val();
       var apartment = $('#dictorganization-apartment').val();

       if (state) {
           fullAddress += state + ', ';
       }

       if (city && city !== state) {
           fullAddress += city + ', ';
       }

       if (district) {
           fullAddress += district + ', ';
       }

       if (locality && (locality !== city && locality !== state)) {
           fullAddress += locality + ', ';
       }

       if (street) {
           fullAddress += street + ', ';
       }

       if (buildingNumber) {
           fullAddress += 'д. ' + buildingNumber + ', ';
       }

       if (building) {
           fullAddress += 'к. ' + building + ', ';
       }

       if (apartment) {
           fullAddress += 'кв. ' + apartment;
       }

       $('#dictorganization-address').val(fullAddress);
    }

    // Ограничения ввода в поля с латинскими символами
    $('#dictorganization-namelatin').on('keypress', checkRegExp);
    $('#dictorganization-addresslatin').on('keypress', checkRegExp);
    $('#dictorganization-locationlatin').on('keypress', checkRegExp);

    // Обработка выбора значения субьекта РФ
    $('#dictorganization-state').on('change', function() {
        // Очистка связанных полей
        $('#dictorganization-city').val('');
        $('#dictorganization-locality').val('');

        var value = $(this).val();

        // Если Москва, СПБ, Севастополь автоматически заполняем поля Город и Населенный пункт

        var cities = ['Москва', 'Санкт-Петербург', 'Севастополь'];

        if (cities.includes(value)) {
            $('#dictorganization-city').val(value);
            $('#dictorganization-locality').val(value);
        }

        generateAddress();
    });

    // Формирование полного адреса при изменении какого-либо компонента
    $('.set-address-block input').on('change', function() {
        generateAddress();
    });

    deprecateSpaceSymbol('#dictorganization-name');
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);

?>