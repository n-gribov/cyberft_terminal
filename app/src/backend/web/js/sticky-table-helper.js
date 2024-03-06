function checkForSelectableDocument() {
    var hasSelectableDocuments = $('.grid-view input:checkbox:visible').size() > 0;
    if (hasSelectableDocuments) {
        $('.grid-view .select-on-check-all, #btnDelete, #delete-selected-documents-button').show();
    }
}

function stickyTableHelperInit() {
    var table = $('.grid-view table');
    table.stickyTableHeaders();

    var fakeInputs = $('.grid-view table .tableFloatingHeader input, .grid-view table .tableFloatingHeader select');

    fakeInputs.each(function() {
        $(this).attr('name', '');
        $(this).attr('id', '');
    });

    $('.tableFloatingHeader #w0-filters').attr('id', '');

    checkForSelectableDocument();
}
