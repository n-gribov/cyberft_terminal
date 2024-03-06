function escapeRegExp(str) {
    return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
}

(function () {
    var mtCollectionObserver = {

        /**
         * все учтенные коллекции за которыми наблюдаем
         */
        collections: [],

        init: function () {
            var self = this;
            $('body').on('click', '.mt-collection .mt_collection_handler', [], this.handler);
        },

        initItem: function(item, id) {
            if (!id) {
               id = item.data('identifier');
            }

            mtCollectionObserver.collections[id] = new mtCollectionItem(item);
        },

        handler: function () {
            var id = $(this).data('bind');
            var action = $(this).data('action');

            if (!mtCollectionObserver.collections[id]) {
                var item = $('#_collection_wrapper' + id);
                if (!item || !item.length) {
                    console.log('Element #_collection_wrapper' + id + ' not found in DOM');
                    return false;
                }
                mtCollectionObserver.initItem(item, id);
            }

            if ('plus' === action) {
                mtCollectionObserver.collections[id].plus();
            } else if ('minus' === action) {
                mtCollectionObserver.collections[id].minus();
            }

            console.log(action + id);

            return false;
        }
    };

    var mtCollectionItem = function ($e) {
        this.container = $e;
        this.formName = $e.data('formname');
        this.identifier = $e.data('identifier');
        this.items = $e.find('> .mt_collection_item');
    };

    mtCollectionItem.prototype = {
        container: undefined,
        formName: undefined,
        identifier: undefined,
        items: [],
        plus: function () {
            var self = this;
            var newFormName = this.formName.replace(/([\s\S]*)\[0\]/m, function (a, b) {
                return b + '[' + self.items.length + ']';
            });

            var newIdentifier = this.identifier.replace(/([\s\S]*)0/m, function (a, b) {
                return b + self.items.length;
            });

            var clone = $(this.items[0]).clone();
            clone
                .find('input, textarea, div, .mt_collection_handler')
                .each(function (index) {
                    var replace;

                    var currentName = $(this).attr('name');
                    if (currentName) {
                        replace = currentName.replace((self.formName), newFormName);
                        $(this).attr('name', replace);
                    }

                    var currentBind = $(this).attr('data-bind');
                    if (currentBind) {
                        replace = currentBind.replace(escapeRegExp(self.identifier), newIdentifier);
                        $(this).attr('data-bind', replace);
                    }

                    // пипец поздно, отрефакторить
                    if (!this.readOnly && $(this).val() && $(this).attr('type') != 'radio') {
                        $(this).val('');
                    }
            })
            .end()
            .find('.mt-collection')
            .each(function(index){
                var currentId = $(this).attr('id');
                var baseId = '_collection_wrapper' + self.identifier;
                var newId = currentId.replace(escapeRegExp(baseId), '_collection_wrapper' + newIdentifier);
                $(this).attr('id', newId);
            })
            .end();

            this.items.last().after(clone);
            this.items.push(clone[0]);
            return false;
        },

	minus: function () {
            if (1 === this.items.length) {
                return false;
            }
            this.items.last().remove();
                this.items.splice(this.items.length - 1, 1);
                return false;
            }
	};

	mtCollectionObserver.init();
})();
