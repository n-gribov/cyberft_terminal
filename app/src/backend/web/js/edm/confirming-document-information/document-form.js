function getFormField(formName, fieldName) {
    return $('[name="' + formName + '[' + fieldName + ']"]');
}

function AttachedFilesListController(
    uploadFormSelector, addButtonSelector, gridViewSelector, storageInputSelector, renderGridViewUrl
) {

    this.$form = $(uploadFormSelector);
    this.$fileInput = this.$form.find('input:file');
    this.$gridView = $(gridViewSelector);
    this.$addButton = $(addButtonSelector);
    this.$storageInput = $(storageInputSelector);
    this.renderGridViewUrl = renderGridViewUrl;

    this.bindToView = function () {
        var self = this;

        this.$addButton.click(function () {
            self.$fileInput.trigger('click');
        });

        this.$fileInput.change(function () {
            if ($(this).val() === '') {
                return;
            }

            self.uploadFile();
        });

        var deleteButtonSelector = this.$gridView.selector + ' .delete-button';
        $(document).on('click', deleteButtonSelector, function () {
            var itemId = $(this).attr('data-id');
            self.deleteItem(itemId);
            self.renderGridView();
            return false;
        });

        this.$form.closest('.modal').on('hide.bs.modal', function () { self.reset() });
    };

    this.uploadFile = function () {
        var self = this;
        var formData = new FormData(this.$form.get(0));
        $.ajax({
            url: this.$form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                self.$form.trigger('reset');
                self.addItem({ id: response.id, name: response.name });
                self.renderGridView(function () { self.$addButton.button('reset'); });
            },
            beforeSend: function () {
                self.$addButton.button('loading');
            },
            error: function () {
                self.$addButton.button('reset');
            }
        });
    };

    this.getList = function () {
        var serializedValue = this.$storageInput.val();
        try {
            return JSON.parse(serializedValue);
        } catch (e) {
            return [];
        }
    };

    this.saveList = function (list) {
        var serializedValue = JSON.stringify(list);
        this.$storageInput.val(serializedValue);
    };

    this.addItem = function (newItem) {
        var list = this.getList();
        list.push(newItem);
        this.saveList(list);
    };

    this.deleteItem = function (id) {
        var newList = this.getList().filter(function (item) {
            return item.id !== id;
        });
        this.saveList(newList);
    };

    this.reset = function () {
        if (this.getList().length > 0) {
            this.saveList([]);
            this.renderGridView();
        }
    };

    this.renderGridView = function (onComplete) {
        var self = this;
        $.ajax({
            url: this.renderGridViewUrl,
            method: 'POST',
            data: {
                list: JSON.stringify(this.getList())
            },
            cache: false,
            success: function (response) {
                var $newGridView = $(response);
                self.$gridView.replaceWith($newGridView);
                self.$gridView = $newGridView;
                if (onComplete) {
                    onComplete();
                }
            }
        });
    };
}

AttachedFilesListController.run = function (params) {
    var controller = new AttachedFilesListController(
        params.uploadFormSelector,
        params.addButtonSelector,
        params.gridViewSelector,
        params.storageInputSelector,
        params.renderGridViewUrl
    );
    controller.bindToView();
};

var attachFileController = {

    formName: 'ConfirmingDocumentInformationItem',

    initialize: function () {

        AttachedFilesListController.run({
            uploadFormSelector:   '#upload-attached-file-form',
            addButtonSelector:    '#add-attached-file-button',
            gridViewSelector:     '#attached-files-grid-view',
            storageInputSelector: '[name="' + this.formName + '[attachedFilesJson]"]',
            renderGridViewUrl:    'render-attached-files'
        });
    }

};
