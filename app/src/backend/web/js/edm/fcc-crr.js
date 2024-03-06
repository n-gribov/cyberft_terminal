/**
 * Для app/src/addons/edm/views/contract-registration-request/_form.php
 */

// Cелектор кнопок выбора типа паспорта сделки
var radioPassportType = "input[name='ContractRegistrationRequestExt[passportType]']";

// Базовый url для подстановки в адреса получения шаблонов/действий
var baseServiceUrl = '/edm/contract-registration-request/';

// Класс для блоков, которые относятся к типу "Кредитный договор"
var loanClass = '.type-loan';

// Классы таблиц со списками связанных данных
var relatedDataClass = {
    nonresidents: '.nonresidents', // нерезиденты
    tranches: '.tranches', // транши
    paymentSchedule: '.payment-schedule', // график платежей
    nonresidentsCredit: '.nonresidents-credit' // нерезиденты-кредиторы
};

// Адреса для получения шаблонов добавления нового объекта связанных данных
var relatedDataNewTemplateUrl = {
    nonresidents: baseServiceUrl + 'add-related-data?type=nonresidents&credit=0', // нерезиденты
    tranches: baseServiceUrl + 'add-related-data?type=tranches', // транши
    paymentSchedule: baseServiceUrl + 'add-related-data?type=paymentSchedule', // график платежей
    nonresidentsCredit: baseServiceUrl + 'add-related-data?type=nonresidents&credit=1' // нерезиденты-кредиторы
};

// Адреса удаления связанных объектов из документа
var relatedDataDeleteUrl = {
    nonresidents: baseServiceUrl + 'delete-related-data?type=nonresidents', // нерезиденты
    tranches: baseServiceUrl + 'delete-related-data?type=tranches', // транши
    paymentSchedule: baseServiceUrl + 'delete-related-data?type=paymentSchedule', // график платежей
    nonresidentsCredit: baseServiceUrl + 'delete-related-data?type=nonresidentsCredit' // нерезиденты-кредиторы
};

// Адреса получения шаблона формы редактирования связанных данных
var relatedDataUpdateUrl = {
    nonresidents: baseServiceUrl + 'update-related-data?type=nonresidents', // нерезиденты
    tranches: baseServiceUrl + 'update-related-data?type=tranches', // транши
    paymentSchedule: baseServiceUrl + 'update-related-data?type=paymentSchedule', // график платежей
    nonresidentsCredit: baseServiceUrl + 'update-related-data?type=nonresidentsCredit' // нерезиденты-кредиторы
};

// Адрес получения данных по организации
var organizationDataUrl = '/edm/dict-organization/get-organization-data';

// Модальное окно управления связанными данными
var dataModal = '#data-modal';

// Поля для указания адреса организации
var organizationAddressData = ['ogrn', 'dateEgrul', 'inn', 'kpp', 'state', 'district',
    'city', 'locality', 'street', 'buildingNumber', 'building', 'apartment'];

//--------------------------------------------------------------

// Обработка смены типа контракта
$(radioPassportType).on('change', function() {
    checkPassportType();
});

// Определение типа контакта и отображение/скрытие полей
function checkPassportType() {
    var type = $(radioPassportType + ":checked").val();

    // Режим отображения по-умолчанию
    var mode = "none";

    // Если тип - Кредитный договор
    if (type === 'loan') {
        mode = 'block';
    }

    $(loanClass).css('display', mode);
}

// Обработка выбора организации
function applyOrganization(organization) {
    // сброс текущих значений полей
    resetOrganization();

    // Ajax-запрос для получения данных организации
    $.ajax({
        url: organizationDataUrl,
        type: 'get',
        data: 'id=' + organization.id,
        success: function(result) {
            // Преобразование json-ответа в объект
            var organizationData = JSON.parse(result);

            // Заполнение полей данными
            organizationAddressData.forEach(function(item, index) {
                $('#contractregistrationrequestext-' + item.toLowerCase()).val(organizationData[item]).trigger('change');
            });
        }
    });
}

// Сброс данных организации
function resetOrganization() {
    organizationAddressData.forEach(function(item, index) {
        $('#contractregistrationrequestext-' + item.toLowerCase()).val("");

        // Очистка значения элемента с Select2
        if (item == 'state') {
            $('#contractregistrationrequestext-' + item.toLowerCase()).select2("val", "");
        }
    });
}

// Вызов модального окна управления связанными данными
function openDataModal(url, title) {
    $.ajax({
        url: url,
        type: 'get',
        success: function(result) {
            $(dataModal + ' .modal-title').html(title);
            $(dataModal + ' .modal-body').html(result);
            $(dataModal).modal('show');
        }
    });
}

// Вызов модального окна для добавления нового нерезидента
$('.btn-new-nonresident').on('click', function(e) {
    e.preventDefault();

    // Заголовок для модального окна
    var title = $(this).data('title');
    openDataModal(relatedDataNewTemplateUrl.nonresidents, title);
});

// Вызов модального окна для добавления нового транша
$('.btn-new-tranche').on('click', function(e) {
    e.preventDefault();

    // Заголовок для модального окна
    var title = $(this).data('title');
    openDataModal(relatedDataNewTemplateUrl.tranches, title);
});

// Вызов модального окна для добавления нового элемента графика платежа
$('.btn-new-payment-schedule').on('click', function(e) {
    e.preventDefault();

    // Заголовок для модального окна
    var title = $(this).data('title');
    openDataModal(relatedDataNewTemplateUrl.paymentSchedule, title);
});

// Вызов модального окна для добавления нового нерезидента-кредитора
$('.btn-new-nonresident-credit').on('click', function(e) {
    e.preventDefault();

    // Заголовок для модального окна
    var title = $(this).data('title');
    openDataModal(relatedDataNewTemplateUrl.nonresidentsCredit, title);
});

// Сброс действия submit в форме управления нерезидентами
$(dataModal).on('hidden.bs.modal', function (e) {
    $('body').off('click', '.btn-submit-form');
});


// Удаление строки связанных данных со страницы документа
function deleteRelatedDataItem(url, uuid, dataTableClass) {
    $.ajax({
        url: url,
        data: "uuid=" + uuid,
        type: 'get',
        success: function(result) {
            // Отображение таблицы операций
            $(dataTableClass).html(result);
        }
    });
}

// Вызов формы для редактирования связанных данных документа
function updateRelatedDataItem(url, uuid, title) {
    $.ajax({
        url: url,
        data: "uuid=" + uuid,
        type: 'get',
        success: function(result) {
            $(dataModal + ' .modal-title').html(title);
            $(dataModal + ' .modal-body').html(result);
            $(dataModal).modal('show');
        }
    });
}

// Удаление строки с нерезидентом
$('body').on('click', '.delete-nonresident', function(e) {
    e.preventDefault();
    var uuid = $(this).data('uuid');
    var credit = $(this).data('credit');
    var url = '';
    var dataClass = '';

    // Разные параметры в зависимости от типа нерезидента (обычный/кредитор)
    if (credit) {
        url = relatedDataDeleteUrl.nonresidentsCredit;
        dataClass = relatedDataClass.nonresidentsCredit;
        dataType = 'nonresidentsCredit';
    } else {
        url = relatedDataDeleteUrl.nonresidents;
        dataClass = relatedDataClass.nonresidents;
        dataType = 'nonresidents';
    }

    deleteRelatedDataItem(url, uuid, dataClass);

    // Удаление из кэша
    $.post('/wizard-cache/crr', {Delete: {type: dataType, uuid: uuid}});
});

// Удаление строки с траншем
$('body').on('click', '.delete-tranche', function(e) {
    e.preventDefault();
    var uuid = $(this).data('uuid');

    deleteRelatedDataItem(relatedDataDeleteUrl.tranches, uuid, relatedDataClass.tranches);

    // Удаление из кэша
    $.post('/wizard-cache/crr', {Delete: {type: "tranches", uuid: uuid}});
});

// Удаление строки с элементом графика платежей
$('body').on('click', '.delete-payment-schedule', function(e) {
    e.preventDefault();
    var uuid = $(this).data('uuid');

    deleteRelatedDataItem(relatedDataDeleteUrl.paymentSchedule, uuid, relatedDataClass.paymentSchedule);

    // Удаление из кэша
    $.post('/wizard-cache/crr', {Delete: {type: "paymentSchedule", uuid: uuid}});
});

// Обновление строки с нерезидентом
$('body').on('click', '.update-nonresident', function(e) {
    e.preventDefault();

    var uuid = $(this).data('uuid');
    var title = $(this).data('title');

    var credit = $(this).data('credit');
    var url = '';

    // Разные параметры в зависимости от типа нерезидента (обычный/кредитор)
    if (credit) {
        url = relatedDataUpdateUrl.nonresidentsCredit;
    } else {
        url = relatedDataUpdateUrl.nonresidents;
    }

    updateRelatedDataItem(url, uuid, title);
});

// Обновление строки с траншем
$('body').on('click', '.update-tranche', function(e) {
    e.preventDefault();

    var uuid = $(this).data('uuid');
    var title = $(this).data('title');

    updateRelatedDataItem(relatedDataUpdateUrl.tranches, uuid, title);
});

// Обновление строки с элементом графика платежа
$('body').on('click', '.update-payment-schedule', function(e) {
    e.preventDefault();

    var uuid = $(this).data('uuid');
    var title = $(this).data('title');

    updateRelatedDataItem(relatedDataUpdateUrl.paymentSchedule, uuid, title);
});

// Submit-событие для форм управления связанными данными
$('body').on('click', dataModal + ' .btn-submit-form', function(e) {
    var form = $('#related-data-form');
    var formData = form.serialize();
    var formAction = form.attr('action');
    var formMethod = form.attr('method');
    var type = form.data('type');

    $.ajax({
        url: formAction + "?type=" + type,
        data: formData,
        type: formMethod,
        success: function(result) {
            // Если успешно, закрываем форму и отображаем результат
            if (result.status === "ok") {
                $(dataModal).modal('hide');

                // Отображение таблицы данных
                $(relatedDataClass[type]).html(result.content);

                // Кэширование данных формы
                $.post('/wizard-cache/crr', formData);
            } else if (result.status === "error") {
                $('body').off('click', '.btn-submit-form');

                // Иначе перерисовка формы
                $(dataModal + ' .modal-body').html(result.content);
            }
        }
    });
});

// Обработка выбора субъекта РФ, автоподстановка города и населенного пункта
$('#contractregistrationrequestext-state').on('change', function() {
    // Очистка связанных полей
    $('#contractregistrationrequestext-city').val('');
    $('#contractregistrationrequestext-locality').val('');

    var value = $(this).val();

    // Если Москва, СПБ, Севастополь автоматически заполняем поля Город и Населенный пункт
    var cities = ['Москва', 'Санкт-Петербург', 'Севастополь'];

    if (cities.includes(value)) {
        $('#contractregistrationrequestext-city').val(value);
        $('#contractregistrationrequestext-locality').val(value);
    }
});

checkPassportType();

// Запись кэша формы
$('.edm-crr-form').change(function() {
    $.post('/wizard-cache/crr', $(this).serialize());
});