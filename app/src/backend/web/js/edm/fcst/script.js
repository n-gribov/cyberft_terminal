var accountsListUrl = '/edm/edm-payer-account/list';
//var organizationAccountField = '#foreigncurrencyselltransit-organizationaccount';
var bankBikField = '#foreigncurrencyselltransit-bankbik';
var transitAccountField = '#foreigncurrencyselltransit-transitaccount';
var foreignAccountField = '#foreigncurrencyselltransit-foreignaccount';
var accountField = '#foreigncurrencyselltransit-account';
var commissionAccountField = '#foreigncurrencyselltransit-commissionaccount';
var organizationField = '#foreigncurrencyselltransit-organizationid';
var fcst_bankBik = null;
var fcst_organizationId = null;

// Определение состояния поля выбора организации и связанных с ним полей
function fcst_organizationDataStatus() {
    $('.fcst-org-inn').text('');
    $('.fcst-org-address').text('');

    $('.organization-data-block').hide();

    fcst_organizationId = $(organizationField).val();

    if (!fcst_organizationId) {
        return false;
    }

    // Получение данных по организации
    $.get('/edm/dict-organization/get-organization-data', {id: fcst_organizationId}).done(function(data) {
        data = JSON.parse(data);

        $('.fcst-org-inn').text(data.inn);
        $('.fcst-org-address').text(data.address);

        $('.organization-data-block').show();
    });
}

// Получение расчетных счетов организации
//function fcst_organizationAccounts() {
//    if (fcst_organizationId) {
//        return accountsListUrl + '?currency=1&organizationId=' + fcst_organizationId;
//    }
//}

// Url для получения списка транзитных счетов
function fcst_transitAccounts() {
    var s = accountsListUrl + '?exceptCurrency=1';
    if (fcst_bankBik) {
        s += '&bankBik=' + fcst_bankBik;
    }
    return s;
}

// Url для получения списка валютных счетов
function fcst_foreignAccounts() {
    return accountsListUrl + '?exceptCurrency=1&bankBik=' + fcst_bankBik;
}

// Url расчетных счетов
function fcst_accounts()
{
    return accountsListUrl + '?currency=1&bankBik=' + fcst_bankBik;
}

function fcst_commissionAccounts()
{
    return accountsListUrl + '?currency=1&bankBik=' + fcst_bankBik;
}

// Событие выбора организации
function fcst_applyOrganization() {
    fcst_bankBik = null;
    //$('#foreigncurrencyselltransit-organizationaccount').val(null).trigger('change');
    $(bankBikField).val(null).trigger('change');
    $(bankBikField + ' option').remove();
    $(bankBikField).attr('disabled', false);

    clearAccounts();

    fcst_organizationDataStatus();

    // Проверка на количество счетов по организации, если счет один, сразу подставляем его
    $.get(accountsListUrl, {currency: 1, organizationId: fcst_organizationId})
        .done(function(answer) {
            if (answer.results.length === 0 || answer.results.length > 1) {
                var biks = [];
                for (var i in answer.results) {
                    let bank = answer.results[i].bank;
                    if (biks[bank.bik] === undefined) {
                        biks[bank.bik] = true;
                        // add bank to list
                        preselectStaticItem(bankBikField, bank.bik, bank.name, false);
                    }
                }
                $(bankBikField).val(null).trigger('change');
                return false;
            }

            element = answer.results[0];
            fcst_bankBik = element.bankBik;
            preselectStaticItem(bankBikField, fcst_bankBik, element.bank.name, true);
//            $(commissionAccountField).attr('disabled', false);
//            preselectItem(commissionAccountField, {
//                id: element.id,
//                name: element.name,
//                text: element.name + ', ' + element.number + ', ' + element.currencyInfo.name,
//                bankBik: fcst_bankBik,
//                currencyInfo: element.currencyInfo
//            });


//            preselectItem(organizationAccountField, {
//                id: element.id,
//                name: element.name,
//                text: element.name + ', ' + element.number + ', ' + element.currencyInfo.name,
//                bankBik: element.bankBik,
//                currencyInfo: element.currencyInfo
//            });
        });
}

function clearAccounts() {
    $(transitAccountField + ' option').remove();
    $(foreignAccountField + ' option').remove();
    $(accountField + ' option').remove();
    $(commissionAccountField + ' option').remove();
}

function fcst_applyBank() {
    
    fcst_bankBik = $(bankBikField).val();
   
    clearAccounts();

    // Проверка количества транзитных счетов, если один, сразу подставляем его
    checkAccountsCount(fcst_transitAccounts(), transitAccountField);

    // Проверка количества валютных счетов, если один, сразу подставляем его
    checkAccountsCount(fcst_foreignAccounts(), foreignAccountField);

    // Проверка количества расчетных счетов, если один, сразу подставляем его
    checkAccountsCount(fcst_accounts(), accountField);
    
    checkAccountsCount(fcst_commissionAccounts(), commissionAccountField);

    $(transitAccountField).attr('disabled', false);
    $(foreignAccountField).attr('disabled', false);
    $(accountField).attr('disabled', false);
    $(commissionAccountField).attr('disabled', false);

    // Счет комиссии по умолчанию равен выбранному расчетному счету организации
//    preselectItem(commissionAccountField, {
//        id: e.id,
//        text: e.name + ', ' + e.id + ', ' + e.currencyInfo.name,
//        bankBik: e.bankBik,
//        currencyInfo: e.currencyInfo
//    });
}

//function fcst_applyOrganizationAccount(e) {
//    fcst_bankBik = e.bankBik;
//
//    // Сброс значения транзитного счета
//    $(transitAccountField).val(null).trigger('change');
//    $(foreignAccountField).val(null).trigger('change');
//    $(accountField).val(null).trigger('change');
//
//    // Проверка количества транзитных счетов, если один, сразу подставляем его
//    checkAccountsCount(fcst_transitAccounts(), transitAccountField);
//
//    // Проверка количества валютных счетов, если один, сразу подставляем его
//    checkAccountsCount(fcst_foreignAccounts(), foreignAccountField);
//
//    // Проверка количества расчетных счетов, если один, сразу подставляем его
//    checkAccountsCount(fcst_accounts(), accountField);
//
//    // Счет комиссии по умолчанию равен выбранному расчетному счету организации
//    preselectItem(commissionAccountField, {
//        id: e.id,
//        text: e.name + ', ' + e.id + ', ' + e.currencyInfo.name,
//        bankBik: e.bankBik,
//        currencyInfo: e.currencyInfo
//    });
//}


// Проверка количества счетов
function checkAccountsCount(url, field) {
    $.get(url).done(function(answer) {
        if (answer.results.length > 1) return false;

        if (answer.results.length === 0) {

           if (field === transitAccountField || field === accountField) {

               if (field === transitAccountField) {
                   errorMsg = 'Транзитный счет не указан в настройках';
               } else if (field === accountField) {
                   errorMsg = 'Расчетный счет не указан в настройках';
               }

               var parentDiv = $(field).parent();
               parentDiv.addClass('has-error');
               parentDiv.children('.help-block').text(errorMsg);
           }

            return false;
        }

        element = answer.results[0];

        preselectItem(field, {
            id: element.id,
            name: element.name,
            text: element.name + ', ' + element.number + ', ' + element.currencyInfo.name,
            bankBik: element.bankBik,
            currencyInfo: element.currencyInfo
        });
    });
}

function preselectStaticItem(field, id, text, selected = false) {
    if (field === undefined) {
        console.log('trying to static preselect undefined field with text ' + text);
        return;
    }
    var option = new Option(text, id, selected, selected);
    var f = $(field).append(option);
    if (selected) {
        f.trigger('change');
    }
}

// Выбор определенного элемента в списке
function preselectItem(field, data) {
    if (field === undefined) {
        console.log('trying to preselect undefined field with data ' + data);
        return;
    }
    var option = new Option(data.text, data.id, true, true);
    $(field).append(option).trigger('change');
    $(field).trigger({
        type: 'select2:select',
        params: {
            data: {id: data.id, text: data.text, bankBik: data.bankBik, name: data.name, currencyInfo: data.currencyInfo}
        }
    });
}

function fcst_transitAccountStatus() {
//    var transitAccount = $('#foreigncurrencyselltransit-transitaccount').val();
//    var disabled = foreignAccount.length === 0;
//    $('#foreigncurrencyselltransit-amount').attr('disabled', disabled);
}

function fcst_foreignAccountStatus() {
    var foreignAccount = $(foreignAccountField).val();
    var disabled = (foreignAccount === null || foreignAccount.length === 0);
    $('#foreigncurrencyselltransit-amounttransfer').attr('disabled', disabled);
}

function fcst_checkOrganizationPreselect() {
    $(bankBikField).attr('disabled', true);
    $(transitAccountField).attr('disabled', true);
    $(foreignAccountField).attr('disabled', true);
    $(accountField).attr('disabled', true);
    $(commissionAccountField).attr('disabled', true);
    var organizations = $(organizationField).children('option');
    if (organizations.length === 2) {
        value = $(organizations[1]).val();
        $(organizationField).val(value).trigger('change');
        fcst_applyOrganization();
    }
}

// Инициализация событий fcst-документа
function initFCSTDocument(type, isNew) {
    if (isNew) {
        fcst_checkOrganizationPreselect();
        fcst_organizationDataStatus();
        fcst_transitAccountStatus();
        fcst_foreignAccountStatus();
    } else {
        $(organizationField).attr('disabled', true);
        $(bankBikField).attr('disabled', true);
//      var account = $('#foreigncurrencyselltransit-transitaccount').val();

//        if (account.length === 0) {
//            return false;
//        }
//
//        $.get('/edm/edm-payer-account/get-account-data', {number: account}).done(function(data) {
//            fcst_bankBik = data.bankBik;
//        });
    }
}

