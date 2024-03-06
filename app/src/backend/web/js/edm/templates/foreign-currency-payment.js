// Просмотр шаблона валютных платежей
fcpTemplateViewBtn.on('click', function(e) {
    e.preventDefault();

    var id = $(this).data('id');
    var name = $(this).data('name');

    showEdmTemplateViewModal(id, name, 'ForeignCurrencyPayment', 0);
});

// Очистка содержимого футера модального окна после закрытия
fcpTemplateViewModal.on('hide.bs.modal', function (e) {
    $(this).find('.modal-footer').html('');
});


// Вызов формы редактирования шаблона из журнала шаблонов
fcpTemplateJournalUpdateBtn.on('click', function(e) {
    e.preventDefault();
    templateId = $(this).data('id');

    formUpdateTemplateJournal(fcpTemplateFormModal, fcpTemplateFormUrl, templateId);
});

// Вызов формы редактирования шаблона из окна просмотра
$('body').on('click', fcpTemplateModalUpdateBtn, function(e) {
    e.preventDefault();
    templateId = $(this).data('id');

    formUpdateTemplateFromView(fcpTemplateViewModal, fcpTemplateFormModal, fcpTemplateFormUrl, templateId);
});

// Вызов формы создания нового шаблона валютного платежа
fcpTemplateNewBtn.on('click', function(e) {
    e.preventDefault();

    formNewTemplate(fcpTemplateFormModal, fcpTemplateFormUrl);
});