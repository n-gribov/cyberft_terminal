debitAccount = '#foreigncurrencyconversion-debitaccount';
debitAccountAmount = '#foreigncurrencyconversion-debitamount';
debitAccountCurrency = '.fcvn-debit-account-currency';
urlAccounts = '/edm/edm-payer-account/list';

creditAccount = '#foreigncurrencyconversion-creditaccount';
creditAccountAmount = '#foreigncurrencyconversion-creditamount';
creditAccountCurrency = '.fcvn-credit-account-currency';

// Определение состояния поля выбора организации и связанных с ним полей
function fcvn_organizationDataStatus() {
    $('.fcvn-org-inn').text('');
    $('.fcvn-org-kpp').text('');
    $('.fcst-org-address').text('');

    $('.organization-data-block').hide();

    var organizationId = $('#foreigncurrencyconversion-organizationid').val();

    if (!organizationId) {
        return false;
    }

    // Получение данных по организации
    $.get('/edm/dict-organization/get-organization-data', {id: organizationId}).done(function(data) {
        data = JSON.parse(data);

        $('.fcvn-org-inn').text(data.inn);
        $('.fcvn-org-kpp').text(data.kpp);
        $('.fcvn-org-address').text(data.address);

        $('.organization-data-block').show();
    });
}

function fcvn_checkOrganizationPreselect() {
    var organizationField = $('#foreigncurrencyconversion-organizationid');

    var organizations = organizationField.children('option');

    if (organizations.length == 2) {
        value = $(organizations[1]).val();

        organizationField.val(value).trigger('change');
        fcvn_applyOrganization();
    }
}

// Url для получения списка счетов зачисления
function fcvn_getDebitAccounts() {
    var organizationId = $('#foreigncurrencyconversion-organizationid').val();
    var url = urlAccounts;
    url += '?exceptCurrency=1&organizationId='+organizationId;

    var creditAccountVal = $(creditAccount).val();

    if (creditAccountVal.length =! 0) {
        url += '&exceptNumber=' + creditAccountVal;
    }

    return url;
}

// Url для получения списка счетов списания
function fcvn_getCreditAccounts() {
    var organizationId = $('#foreigncurrencyconversion-organizationid').val();
    var url = urlAccounts;
    url += '?exceptCurrency=1&organizationId='+organizationId;

    var debitAccountVal = $(debitAccount).val();

    if (debitAccountVal.length =! 0) {
        url += '&exceptNumber=' + debitAccountVal;
    }

    return url;
}

// Url для получения списка счетов выбранной организации
function fcvn_getAccounts() {
    var organizationId = $('#foreigncurrencyconversion-organizationid').val();
    var url = urlAccounts;
    url += '?organizationId='+organizationId;

    return url;
}

function fcvn_resetDebitAccount() {
    $(debitAccountAmount).val('').attr('disabled', true);
    $(debitAccountCurrency).text('');
}

function fcvn_resetCreditAccount() {
    $(creditAccountAmount).val('').attr('disabled', true);
    $(creditAccountCurrency).text('');
}

function fcvn_applyDebitAccount(data) {
    $(debitAccountCurrency).text(data.currencyInfo.name);
}

function fcvn_applyCreditAccount(data) {
    $(creditAccountCurrency).text(data.currencyInfo.name);
}

// Событие выбора организации
function fcvn_applyOrganization() {
    fcvn_organizationDataStatus();
}

function fcvn_check_amounts() {
    debitAmountValue = $(debitAccountAmount).val();
    creditAmountValue = $(creditAccountAmount).val();

    $(debitAccountAmount).attr('disabled', false);
    $(creditAccountAmount).attr('disabled', false);

    if (debitAmountValue.length) {
        $(creditAccountAmount).attr('disabled', true);
    } else if (creditAmountValue.length) {
        $(debitAccountAmount).attr('disabled', true);
    }
}

// Инициализация событий fcst-документа
function initFCVNDocument(type, isNew) {
    if (isNew) {
        fcvn_checkOrganizationPreselect();
    }

    fcvn_organizationDataStatus();

    $(debitAccountAmount).change(function(e) {
        fcvn_check_amounts();
    });

    $(creditAccountAmount).change(function(e) {
        fcvn_check_amounts();
    });

    fcvn_check_amounts();
}

