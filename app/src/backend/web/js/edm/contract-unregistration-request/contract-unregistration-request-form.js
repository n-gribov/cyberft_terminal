function getFormField(formName, fieldName) {
    return $('[name="' + formName + '[' + fieldName + ']"]');
}

function NestedItemsListController(formModalSelector, addButtonSelector, gridViewSelector, storageInputSelector, renderGridViewUrl, renderGridViewParamsCallback) {
    this.$modal = $(formModalSelector);
    this.$form = $(formModalSelector).find('form');
    this.$gridView = $(gridViewSelector);
    this.$addButton = $(addButtonSelector);
    this.$saveButton = $(formModalSelector).find('.save-button');
    this.$storageInput = $(storageInputSelector);
    this.renderGridViewUrl = renderGridViewUrl;
    this.renderGridViewParamsCallback = renderGridViewParamsCallback;

    this.bindToView = function () {
        var self = this;

        this.$addButton.click(this.showFormModal.bind(this));

        this.$saveButton.click(function () {
            self.$form.trigger('submit');
        });

        this.$form.on('beforeSubmit', function () {
            self.saveItem();
            self.renderGridView(self.hideFormModal.bind(self));
            self.$modal.modal('hide');
            return false;
        });

        var updateButtonSelector = this.$gridView.selector + ' .update-button';
        $(document).on('click', updateButtonSelector, function () {
            var itemId = $(this).attr('data-id');
            var item = self.getItem(itemId);
            self.showFormModal(item);
            return false;
        });

        var deleteButtonSelector = this.$gridView.selector + ' .delete-button';
        $(document).on('click', deleteButtonSelector, function () {
            var itemId = $(this).attr('data-id');
            self.deleteItem(itemId);
            self.renderGridView();
            return false;
        });
    };

    this.showFormModal = function (item) {
        this.$form.trigger('reset');
        this.$form.find('input, select, textarea').val('');
        if (item) {
            for (var attributeName in item) {
                var inputSelector = this.getFormFieldSelector(attributeName);
                if (!inputSelector) {
                    continue;
                }
                $(inputSelector).val(item[attributeName]);
            }
        }
        this.$modal.modal('show');
    };

    this.hideFormModal = function () {
        this.$modal.modal('hide');
    };

    this.getFormFieldSelector = function (attributeName) {
        var attributes = this.$form.yiiActiveForm('data').attributes;
        for (var i = 0; i < attributes.length; i++) {
            var attribute = attributes[i];
            if (attribute.name === attributeName) {
                return attribute.input;
            }
        }
        return null;
    };

    this.getFormData = function () {
        var attributes = this.$form.yiiActiveForm('data').attributes;
        return attributes.reduce(
            function (data, attribute) {
                data[attribute.name] = attribute.value;
                return data;
            },
            {}
        );
    };

    this.getNextItemId = function (list) {
        var maxId = list.reduce(
            function (maxId, item) {
                return item.id > maxId ? item.id : maxId;
            },
            0
        );
        return maxId + 1;
    };

    this.getList = function () {
        var serializedValue = this.$storageInput.val();
        try {
            return JSON.parse(serializedValue);
        } catch (e) {
            return [];
        }
    };

    this.getItem = function (id) {
        var list = this.getList();
        for (var i = 0; i < list.length; i++) {
            var item = list[i];
            if (item.id == id) {
                return item;
            }
        }
        return null;
    };

    this.saveList = function (list) {
        var serializedValue = JSON.stringify(list);
        this.$storageInput.val(serializedValue);
    };

    this.saveItem = function () {
        var newItem = this.getFormData();
        var list = this.getList();
        if (newItem.id) {
            list = list.map(function (item) {
                return item.id == newItem.id ? newItem : item;
            });
        } else {
            newItem.id = this.getNextItemId(list);
            list.push(newItem);
        }
        this.saveList(list);
    };

    this.deleteItem = function (id) {
        var newList = this.getList().filter(function (item) {
            return item.id != id;
        });
        this.saveList(newList);
    };

    this.renderGridView = function (onComplete) {
        var self = this;

        var data = {};
        if (this.renderGridViewParamsCallback) {
            data = this.renderGridViewParamsCallback.call()
        }
        data.list = JSON.stringify(this.getList());

        $.ajax({
            url: this.renderGridViewUrl,
            method: 'POST',
            data: data,
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

NestedItemsListController.run = function (params) {
    var controller = new NestedItemsListController(
        params.formModalSelector,
        params.addButtonSelector,
        params.gridViewSelector,
        params.storageInputSelector,
        params.renderGridViewUrl,
        params.renderGridViewParamsCallback
    );
    controller.bindToView();
};

function AttachedFilesListController(uploadFormSelector, addButtonSelector, gridViewSelector, storageInputSelector, renderGridViewUrl) {
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

var ContractUnregistrationRequestForm = {

    formName: 'ContractUnregistrationRequestForm',

    toggleSelectedOrganization: function () {
        var $organizationSelect = getFormField(this.formName, 'organizationId');
        var organizationId = $organizationSelect.val();
        $('.organization-info').addClass('hidden');
        if (organizationId) {
            $('.organization-info[data-id=' + organizationId + ']').removeClass('hidden');
        }
    },

    initialize: function () {
        getFormField(this.formName, 'organizationId').change(this.toggleSelectedOrganization.bind(this));

        this.toggleSelectedOrganization();

        NestedItemsListController.run({
            formModalSelector:    '#contract-form-modal',
            addButtonSelector:    '#add-contract-button',
            gridViewSelector:     '#contracts-grid-view',
            storageInputSelector: '[name="' + this.formName + '[contractsJson]"]',
            renderGridViewUrl:    'render-contracts'
        });

        AttachedFilesListController.run({
            uploadFormSelector:   '#upload-attached-file-form',
            addButtonSelector:    '#add-attached-file-button',
            gridViewSelector:     '#attached-files-grid-view',
            storageInputSelector: '[name="' + this.formName + '[attachedFilesJson]"]',
            renderGridViewUrl:    'render-attached-files'
        });
    }
};

ContractUnregistrationRequestForm.initialize();
