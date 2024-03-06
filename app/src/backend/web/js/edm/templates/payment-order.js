// Просмотр шаблона платежных поручений
paymentOrderTemplateViewBtn.on('click', function(e) {
    e.preventDefault();

    var id = $(this).data('id');
    var name = $(this).data('name');
    var isOutdated = $(this).closest('tr').data('is-outdated');

    showEdmTemplateViewModal(id, name, 'PaymentOrder', isOutdated);
});

// Очистка содержимого футера модального окна после закрытия
paymentOrderTemplateViewModal.on('hide.bs.modal', function (e) {
    $(this).find('.modal-footer').html('');
});

// Вызов формы редактирования шаблона из окна просмотра
$('body').on('click', paymentOrderTemplateModalUpdateBtn, function(e) {
    e.preventDefault();
    templateId = $(this).data('id');

    formUpdateTemplateFromView(paymentOrderTemplateViewModal, paymentOrderTemplateFormModal, paymentOrderTemplateFormUrl, templateId);
});

// Событие закрытия окна с формой редактирования/создания шаблона платежных поручений
paymentOrderTemplateFormModal.on('hide.bs.modal', function(event) {
    // Игнорируем событие для модалки редактирования получателя, встроенной в модалку шаблона
    if (event.target.id !== 'edmTemplatePOModal') {
        return;
    }

    if (paymentOrderTemplateFormModal.find('in') && paymentOrderTemplateFormModal.find('in').length === 0) {
        $('.modal-backdrop:first').remove();
    }

    location.reload();
});

// Вызов формы редактирования шаблона из журнала шаблонов
paymentOrderTemplateJournalUpdateBtn.on('click', function(e) {
    e.preventDefault();
    templateId = $(this).data('id');

    formUpdateTemplateJournal(paymentOrderTemplateFormModal, paymentOrderTemplateFormUrl, templateId);
});

// Вызов форма создания нового шаблона платежного поручения
paymentOrderTemplateNewBtn.on('click', function(e) {
    e.preventDefault();

    formNewTemplate(paymentOrderTemplateFormModal, paymentOrderTemplateFormUrl);
});

deprecateSpaceSymbol('#edmTemplatePOModal #paymentordertype-paymentpurpose');