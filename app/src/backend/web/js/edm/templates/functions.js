// Создание управляющих кнопок для футера модального окна
function generateModalFooter(id, deleteUrl, createUrl, isOutdated) {
    var content = '<a class="btn btn-success edm-template-view-modal-update" href="#" data-id="{id}">Редактировать</a>' +
        '<a class="btn btn-danger" href="{deleteUrl}{id}" data-confirm="Are you sure you want to delete this item?" data-method="post">Удалить</a>' +
        '<a class="btn btn-success template-load-link" href="{createUrl}{id}" data-is-outdated="' + isOutdated + '">Создать документ</a>';

    content = content.split('{id}').join(id);
    content = content.split('{deleteUrl}').join(deleteUrl);
    content = content.split('{createUrl}').join(createUrl);

    return content;
}

// Открытие модального окна просмотра шаблона
function showEdmTemplateViewModal(id, name, type, isOutdated)
{
    if (type === 'PaymentOrder') {
        modal = paymentOrderTemplateViewModal;
        viewUrl = paymentOrderTemplateViewUrl;
        deleteUrl = paymentOrderTemplateDeleteUrl;
        createUrl = paymentOrderTemplateCreateUrl;
    } else if(type === 'ForeignCurrencyPayment') {
        modal = fcpTemplateViewModal;
        viewUrl = fcpTemplateViewUrl;
        deleteUrl = fcpTemplateDeleteUrl;
        createUrl = fcpTemplateCreateUrl;
    }

    modal.find('.modal-body').html('');

    $.ajax({
        url: viewUrl + id,
        type: 'get',
        success: function(answer){
            // Добавляем html содержимое на страницу формы
            modal.find('.modal-body').html(answer);

            var footerHtml = generateModalFooter(id, deleteUrl, createUrl, isOutdated);

            modal.find('.modal-footer').html(footerHtml);
            modal.find('.modal-title').html(name);
        }
    });

    modal.modal('show');
}

// Вызов формы создания нового шаблона
function formNewTemplate(modal, url) {
    modal.find('.modal-body').html('');

    $.ajax({
        url: url,
        type: 'get',
        success: function(answer){
            // Добавляем html содержимое на страницу формы
            modal.find('.modal-body').html(answer);
        }
    });

    modal.modal('show');
}

// Вызов формы редактирования шаблона из журнала шаблонов
function formUpdateTemplateJournal(modal, url, templateId) {
    modal.find('.modal-body').html('');
    modal.modal('show');

    $.ajax({
        url: url + templateId,
        type: 'get',
        success: function(answer){
            // Добавляем html содержимое на страницу формы
            modal.find('.modal-body').html(answer);
        }
    });
}

// Вызов формы редактирования шаблона из окна просмотра
function formUpdateTemplateFromView(modalView, modal, url, templateId) {
    modalView.modal('hide');
    modal.find('.modal-body').html('');
    modal.modal('show');

    $.ajax({
        url: url + templateId,
        type: 'get',
        success: function(answer){
            // Добавляем html содержимое на страницу формы
            modal.find('.modal-body').html(answer);
        }
    });
}