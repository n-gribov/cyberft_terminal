function showTableRecord(documentId, fieldId, index) {
    $.ajax({
        url: '/edm/vtb-documents/view-table-record',
        method: 'GET',
        data: {
            id: documentId,
            fieldId: fieldId,
            index: index
        },
        success: function (response) {
            var $modalPlaceholder = $('#view-table-record-modal-placeholder');
            $modalPlaceholder.html(response);
            $modalPlaceholder.find('.modal').modal('show');
        }
    });
}

$(document).ready(function () {
    $('.show-table-record-button').click(function () {
        var tr = $(this).closest('tr');
        var documentId = tr.data('document-id');
        var fieldId = tr.data('field-id');
        var index = tr.data('index');
        showTableRecord(documentId, fieldId, index);

        return false;
    });

    $('.table-records-grid tbody tr').dblclick(function () {
        var documentId = $(this).data('document-id');
        var fieldId = $(this).data('field-id');
        var index = $(this).data('index');
        showTableRecord(documentId, fieldId, index);
    });
});
