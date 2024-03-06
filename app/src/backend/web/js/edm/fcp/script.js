// Url получения Swift-информации по номеру счета плательщика
var fcp_swiftBicOrganizationInfoUrl = '/edm/edm-payer-account/get-swiftbic-organization?accountNumber=';

// Url получения Swift-информации по банку
var fcp_bankSwiftInfoUrl = '/edm/foreign-currency-operation-wizard/get-swift-bank-info?swiftInfo=';

// Текст ошибки получения Swift-информации
var fcp_swiftBicErrorMsg = 'Не удалось подобрать swift bic для банка плательщика. Данное поле не будет указано в документе';

// Обработка выбора плательщика
function fcp_applyPayer(payer, type) {
    // Сброс текущих выбранных значений
    fcp_resetPayer();

    // Заполнение данных плательщика
    $('#' + type + '-payeraccount').val(payer.number);
    $('#' + type + '-currency-commission').val(payer.currencyInfo.name);
    $('#' + type + '-payerinn').val(payer.contractor.inn);
    $('#' + type + '-payername').val(payer.contractor.nameLatin);
    $('#' + type + '-payeraddress').val(payer.contractor.addressLatin);
    $('#' + type + '-payerlocation').val(payer.contractor.locationLatin);

    // Получение swift bic банка плательщика
    $.ajax({
        url: fcp_swiftBicOrganizationInfoUrl + payer.number,
        type: 'get',
        success: function(answer) {
            if (answer.length == 0) {
                // Если не удалось подобрать swiftbic для банка плательщика
                $('.fcp-alert-info').text(fcp_swiftBicErrorMsg).toggle();
                $('.fcp-payer-bank-block').hide();

            } else {
                // Заполнение полей банка плательщика
                $('#' + type + '-payerbank').val(answer.bic + answer.branchCode);
                $('#' + type + '-payerbankname').val(answer.name);
                $('#' + type + '-payerbankaddress').val(answer.address);

                $('.fcp-payer-bank-block').show();
            }
        }
    });

    // Отображение блока информации о плательщике
    $('.fcp-payer-info').slideDown('400');
}

// Сброс значения полей выбранного плательщика
function fcp_resetPayer(type) {

    // Поля данных плательщика
    var payerFields = [
        type + '-payeraccount',
        type + '-commission',
        type + '-payerinn',
        type + '-payername',
        type + '-payeraddress',
        type + '-payerlocation',
        type + '-payerbank',
        type + '-payerbankname',
        type + '-payerbankaddress'
    ];

    fcp_resetFields(payerFields);

    // Сброс ошибок полей
    $('.fcp-payer-info').find('.has-error').removeClass('has-error');
    $('.fcp-payer-info').find('.help-block').text('');
    $('.fcp-alert-info').text('').hide('');

    // Скрытие блока информации о плательщике
    $('.fcp-payer-info').slideUp('400');
}

// Обработка выбора банка посредника
function fcp_applyIntermediaryBank(bank, type) {
    // Сброс текущих выбранных значений
    fcp_resetIntermediaryBank(type);

    // Заполнение данных банка плательщика
    $('.fcp-intermediary-bank-radio-swift .bank-info').text(bank.name + ',' + bank.address).show();
}

// Сброс значений банка посредника
function fcp_resetIntermediaryBank(type) {
    var intermediaryBankFields = [
        type + '-intermediarybanknameandaddress'
    ];

    fcp_resetFields(intermediaryBankFields);
    $('.fcp-intermediary-bank-radio-swift .bank-info').text('').hide();
}

// Обработка выбора банка получателя
function fcp_applyBeneficiaryBank(bank, type) {
    // Сброс текущих выбранных значений
    fcp_resetBeneficiaryBank(type);

    $('.beneficiary-bank-radio-swift .bank-info').text(bank.name + ',' + bank.address).show();
}

// Сброс значений банка получателя
function fcp_resetBeneficiaryBank(type) {
    var beneficiaryBankFields = [
        type + '-beneficiarybanknameandaddress'
    ];

    fcp_resetFields(beneficiaryBankFields);
    $('.beneficiary-bank-radio-swift .bank-info').text('').hide();
}

// Сброс значений полей
function fcp_resetFields(fields) {
    fields.forEach(function(item, index) {
        $('#' + item).val('');
    });
}

// Обработка выбора получателя платежа
function fcp_applyBeneficiaryAccount(beneficiary, type) {
    // Сброс текущих выбранных значений
    fcp_resetBeneficiaryAccount(type);

    // Заполнение данных получателя
    $('#' + type + '-beneficiaryaccount').val(beneficiary.account);
    $('#' + type + '-beneficiary').val(beneficiary.description);
}

// Сброс значений получателя
function fcp_resetBeneficiaryAccount(type) {
    var beneficiaryAccountFields = [
        type + '-beneficiaryaccount',
        type + '-beneficiary'
    ];

    fcp_resetFields(beneficiaryAccountFields);
}

// Проверка состояния отображения информации плательщика
function fcp_showStatusPayerInfo(type) {
    var payerAccount = $('#' + type + '-payeraccount').val();

    if (payerAccount.length > 0) {
        $('.fcp-payer-info').show();
    } else {
        $('.fcp-payer-info').hide();
    }
}

// Проверка состояния отображения информации получателя
function fcp_showStatusBeneficiaryInfo(type) {
    var payerAccount = $('#' + type + '-beneficiaryaccountselect').val();
}

function fcp_showStatusIntermediaryBank(type) {
    var intermediaryBank = $('#' + type + '-intermediarybank').val();
    var intermediaryBankNameAndAddress = $('#' + type + '-intermediarybanknameandaddress').val();

    if (intermediaryBank.length != 0 || intermediaryBankNameAndAddress.length != 0) {
        $('.fcp-intermediary-bank-block').show();
    } else {
        $('.fcp-intermediary-bank-block').hide();
    }
}

// Проверка состояния выбора банка посредника
function fcp_statusIntermediaryBankRadio(type) {
    var value = $('.intermediary-bank-radio').find(":checked").val();

    if (value == 'swiftbic') {
        $('.fcp-intermediary-bank-radio-manual').slideUp('400');
        $('.fcp-intermediary-bank-radio-swift').slideDown('400');

        // Очищаем заполненные вручную наименование и адрес компании
        $('#' + type + '-intermediarybanknameandaddress').val('');

    } else if (value == 'manual') {
        $('.fcp-intermediary-bank-radio-manual').slideDown('400');
        $('.fcp-intermediary-bank-radio-swift').slideUp('400');

        // Очищаем выбранный swiftbic
        $('#' + type + '-intermediarybank').select2('val', '');
        $('.fcp-intermediary-bank-radio-swift .bank-info').text('').hide();
    }
}

// Проверка состояния выбора банка получателя
function fcp_statusBeneficiaryBankRadio(type) {
    var value = $('.beneficiary-bank-radio').find(':checked').val();

    if (value == 'swiftbic') {
        $('.beneficiary-bank-radio-manual').slideUp('400');
        $('.beneficiary-bank-radio-swift').slideDown('400');

        // Очищаем заполненные вручную наименование и адрес компании
        $('#' + type + '-beneficiarybankname').val('');
        $('#' + type + '-beneficiarybankaddress').val('');

    } else if (value == 'manual') {
        $('.beneficiary-bank-radio-manual').slideDown('400');
        $('.beneficiary-bank-radio-swift').slideUp('400');

        // Очищаем выбранный swiftbic
        $('#' + type + '-beneficiarybank').select2('val', '');
        $('.beneficiary-bank-radio-swift .bank-info').text('').hide();
    }
}

// Получение Swift-информации по банку
function fcp_getSwiftBankInfo(bankField, bankInfo) {
    var bank = bankField.val();

    if (bank && bank.length > 0) {
        $.ajax({
            url: fcp_bankSwiftInfoUrl + bank,
            type: 'get',
            success: function(result) {
                result = JSON.parse(result);
                bankInfo.text(result.name + ',' + result.address);
            }
        });
    }
}

// Скрытие блока с банком плательщика, если не заполнены данные
function fcp_checkPayerBankInfo(type) {
    var payerBank = $('#' + type + '-payerbank').val();
    var payerBankName = $('#' + type + '-payerbankname').val();
    var payerBankAddress = $('#' + type + '-payerbankaddress').val();

    if (payerBank.length > 0 ||
        payerBankName.length > 0 ||
        payerBankAddress.length > 0) {
        $('.fcp-payer-bank-block').show();
    } else {
        $('.fcp-payer-bank-block').hide();
    }
}

// Обработка выбора комиссии
function fcp_processCommissionSelect(type) {
    var value = $('#' + type + '-commission').val();

    if (value == 'BEN') {
        $('.commission-info-block').slideDown(400);
    } else {
        $('.commission-info-block').slideUp(400);
    }
}

// Валидация полей по маске
function fcp_validateMask(e) {
    // спец. сочетания - не обрабатываем
    if (e.ctrlKey || e.altKey || e.metaKey) {
        return true;
    }
    var allowedChars = $(e.target).data('allowed-chars');
    var reg = new RegExp(allowedChars != null ? '^' + allowedChars + '$' : '^[^а-яА-Я@\$%^#№&<>"*;_!{}]+$');
    var char = String.fromCharCode(e.keyCode || e.charCode);

    if (!char.match(reg)) {
        e.preventDefault();
    }
}

// Валидация счет получателя
function fcp_validateBeneficiaryAccount(e) {
    // спец. сочетания - не обрабатываем
    if (e.ctrlKey || e.altKey || e.metaKey) {
        return true;
    }

    var reg = new RegExp("[A-z0-9/\-\?\:\(\)\.,'\+\{\} ]");
    var char = String.fromCharCode(e.keyCode || e.charCode);

    if (!char.match(reg)) {
        e.preventDefault();
    }
}

// Поведение отображения блока банка посредника
$('body').on('click', '.fcp-intermediary-bank-title', function() {
    $('.fcp-intermediary-bank-block').slideToggle('400');
});

// Изменение валюты комиссии при изменении основной валюты
$('body').on('change', '.foreigncurrencypayment-currency', function() {
    $('.foreigncurrencypayment-currency-commission').val($(this).val());
});

// Переадресация на форму создания документа с учетом данных из шаблона
function applyTemplate(data) {
    window.location.replace(fcpTemplateCreateUrl + data.id);
}

// Инициализация базовых функций и событий для документа fcp и его шаблона
function fcpBaseInit(type) {
    fcp_showStatusPayerInfo(type);
    fcp_showStatusBeneficiaryInfo(type);

    // Определение видимости блока с информацией по банку посреднику
    fcp_showStatusIntermediaryBank(type);

    // Переключение типа заполнения банка посредника
    $('.intermediary-bank-radio').on('change', function() {
        // Текущий выбранный элемент
        fcp_statusIntermediaryBankRadio(type);
    });

    fcp_statusIntermediaryBankRadio(type);

    // Переключение типа заполнения банка посредника
    $('.beneficiary-bank-radio').on('change', function() {
        // Текущий выбранный элемент
        fcp_statusBeneficiaryBankRadio(type);
    });

    fcp_statusBeneficiaryBankRadio(type);

    // Валидация полей согласно маске
    $('body').on('keypress', '.validate-mask', function(e) {
        fcp_validateMask(e);
    });

    $('body').on('keypress', '#' + type + '-beneficiaryaccount', function(e) {
        fcp_validateBeneficiaryAccount(e);
    });

    // Получение информации по выбранным swift-банкам
    fcp_getSwiftBankInfo($('#' + type + '-beneficiarybank'), $('.beneficiary-bank-radio-swift .bank-info'));
    fcp_getSwiftBankInfo($('#' + type + '-intermediarybank'), $('.fcp-intermediary-bank-radio-swift .bank-info'));

    fcp_checkPayerBankInfo(type);

    // Обработка выбора типа комиссии
//    $('#' + type + '-commission').on('change', function(e) {
//        $('#' + type + '-commissionsum').val('');
//        fcp_processCommissionSelect(type);
//    });
//
//    fcp_processCommissionSelect(type);
}

// Инициализация событий для fcp-шаблона
function initFCPTemplate(type) {
    fcpBaseInit(type);

    var modal = $('#edmTemplateFCPModal');

    // Отправка формы
    modal.find('.btn-submit-template').on('click', function (e) {
        e.preventDefault();
        modal.find('#edm-template-fcp-wizard').trigger('submit');
    });

    $('body #edm-template-fcp-wizard').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: "post",
            url: '/edm/payment-order-templates/fcp-save-template',
            data: $('#edm-template-fcp-wizard').serialize(),
            success: function(data) {}
        });
    });

    // Отправка формы и создание нового документа
    modal.find('.btn-submit-create-template').on('click', function(e) {
        e.preventDefault();
        modal.find('.hidden-create-document').val(1);
        modal.find('#edm-template-fcp-wizard').trigger('submit');
    });
}

// Инициализация событий для fcp-документа
function initFCPDocument(type) {
    fcpBaseInit(type);

    // Смена типа операции для переключения между типами документов
    $('.body-select').on('change', function(e) {
        var type = $('#foreigncurrencypaymenttype-operationtype').val();
        $("#fcoCreateModal .modal-body").html('');

        $.ajax({
            url: '/edm/foreign-currency-operation-wizard/create?type=' + type,
            type: 'get',
            success: function(answer) {
                // Добавляем html содержимое на страницу формы
                $('#fcoCreateModal .modal-body').html(answer);
            }
        });
    });

    // Видимость поля ввода названия шаблона
    function templateNameVisibility() {
        var saveTemplate = $('#foreigncurrencypaymenttype-savetemplate').is(':checked');

        if (saveTemplate === true) {
            $('#foreigncurrencypaymenttype-templatename').slideDown('400');
        } else {
            $('#foreigncurrencypaymenttype-templatename').slideUp('400');
            $('#foreigncurrencypaymenttype-templatename').val('');
        }
    }

    templateNameVisibility();

    $('#foreigncurrencypaymenttype-savetemplate').on('change', function(e) {
        templateNameVisibility();
    });
}