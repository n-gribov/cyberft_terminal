<?php

namespace addons\edm\models;

class EdmDocumentTypeGroup
{
    const RUBLE_PAYMENT = 'RublePayment';
    const CURRENCY_PAYMENT = 'CurrencyPayment';
    const CURRENCY_PURCHASE = 'CurrencyPurchase';
    const CURRENCY_SELL = 'CurrencySell';
    const TRANSIT_ACCOUNT_PAYMENT = 'TransitAccountPayment';
    const CURRENCY_CONVERSION = 'CurrencyConversion';
    const CURRENCY_DEAL_INQUIRY = 'CurrencyDealInquiry';
    const CONFIRMING_DOCUMENTS_INQUIRY = 'ConfirmingDocumentsInquiry';
    const LOAN_AGREEMENT_REGISTRATION_REQUEST = 'LoanAgreementRegistrationRequest';
    const CONTRACT_REGISTRATION_REQUEST = 'ContractRegistrationRequest';
    const CONTRACT_UNREGISTRATION_REQUEST = 'ContractUnregistrationRequest';
    const CONTRACT_CHANGE_REQUEST = 'ContractChangeRequest';
    const BANK_LETTER = 'BankLetter';
    const STATEMENT = 'Statement';
    const CANCELLATION_REQUEST = 'CancellationRequest';
}
