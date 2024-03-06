$(document).ready(function () {
    $('body').on('click', 'a[data-load-modal]', function (event) {
        event.preventDefault();
        $.get($(this).attr('href')).done(function (response) {
            var wrapper = $('<div class="modal-wrapper"></div>').appendTo('body');
            wrapper.html(response);
            var modal = wrapper.find('.modal');
            modal.modal('show');
            modal.on('hidden.bs.modal', function () {
                wrapper.remove();
            });
        });
    });
    $('body').on('beforeSubmit', 'form[data-submit-modal]', function (event) {
        var formData = new FormData($(this).get(0));
        var action = $(this).attr('action');

        var elementsToDisable = $(this).find('[data-disable-on-submit]');
        elementsToDisable.prop('disabled', true);

        var self = this;
        $.ajax({
            url: action,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                var wrapper = $(self).closest('.modal-wrapper');
                var oldModal = wrapper.find('.modal');
                oldModal.removeClass('fade');
                oldModal.off('hidden.bs.modal');
                oldModal.modal('hide');

                wrapper.html(response);
                var newModal = wrapper.find('.modal');
                newModal.removeClass('fade');
                newModal.modal('show');
                newModal.addClass('fade');
                $('.modal-backdrop').addClass('fade');
                newModal.on('hidden.bs.modal', function () {
                    wrapper.remove();
                });
            },
            error: function (jqXHR) {
                if (jqXHR.status != 302) {
                    elementsToDisable.prop('disabled', false);
                }
            }
        });
        return false;
    });
});
