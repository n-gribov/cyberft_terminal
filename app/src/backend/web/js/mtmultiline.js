getLimits = (function(myTextarea) {
    var limitsArray = [];
    if (myTextarea) {
            // Получаем параметры ограничения
            var limits = $(myTextarea).data('limit').split(',');
            // Число строк
            limitsArray[0] = parseInt(limits[0]);
            // Число символов в строках (все строки имеют одинаковый максимум)
            limitsArray[1] = parseInt(limits[1]);
    }

    return(limitsArray);
});

isValidLimits = (function(limitsArray) {
    return (limitsArray instanceof Array)
            && limitsArray.length === 2
            && limitsArray[0] && limitsArray[0] > 0
            && limitsArray[1] && limitsArray[1] > 0
    ;
});

/**
 * Функция возвращает позицию каретки в одно- или многострочном поле ввода.
 * Позиция отсчитывается в символах от начала текста.
 * @return integer
 */
getCaretPosition = (function(myTextarea) {
    var caretPosition = 0;
    if (document.selection) {
        // IE Support
        myTextarea.focus();
        var range = document.selection.createRange();
        range.moveStart('character', -myTextarea.value.length);
        caretPosition = range.text.length;
    } else if (myTextarea.selectionStart || myTextarea.selectionStart == '0') {
        // Firefox support
        caretPosition = myTextarea.selectionStart;
    }

    return(caretPosition);
});

/**
 * Функция устанавливает позицию каретки в одно- или многострочном поле ввода.
 * Позиция отсчитывается в символах от начала текста.
 */
setCaretPosition = (function(myTextarea, position) {
    if (myTextarea.setSelectionRange) {
        myTextarea.focus();
        myTextarea.setSelectionRange(position, position);
    } else if (myTextarea.createTextRange) {
        var range = myTextarea.createTextRange();
        range.collapse(true);
        range.moveEnd('character', position);
        range.moveStart('character', position);
        range.select();
    }
});

jQuery(document).ready(function () {

    $('body').on('paste', '.mtmultiline', function(event) {
        var self = this;
        
        setTimeout(function() {
            var text = $(self).val();
            
            text = text.replace(/\n/g, '');
//            var lines = text.split("\n");
            
//            var out = '';
//            for (var i in lines) {
//               var line = lines[i];
//               if (line.startsWith('/')) {
//                   if (i > 0) {
//                       out += "\n";
//                   }
//               } else {
//                   out += ' ';
//               }
//               out += line;
//            }
//
//            lines = out.split("\n");
//            var len = out.length;

            var len = text.length;
            limits = getLimits(event.currentTarget);
            if (!isValidLimits(limits)) {
                return;
            }

            var rows = limits[0];
            var cols = limits[1];
            var is72field = false;

            if ($(self).data('textarea-type') == '72') {
                is72field = true;
            }

//console.log('rows: ' + rows + ' cols: ' + cols + ' is72: ' + is72field);

            var prev = cols;
            var start = 0;
            var arr = [];
            var addText = '';
            var max = cols;

            if (is72field) {
                addText = '/';
                max--;
            }

            for (var i = 1; i <= rows; i++) {

                if (start > len) {
                    break;
                }

                var segment = text.substr(start, max);

                //console.log(segment);

                if (addText !== '') {
                    segment = addText + segment;
                    max = cols;
                }

                arr.push(segment);

                start += prev;

                if (is72field) {

                    if (text[start + 1] !== ' ') {
                        addText = '//';
                        max = cols - 2;
                    } else {
                        addText = '';
                        max = cols;
                    }
                }
            }

            $(self).val(arr.join("\n"));

        }, 0);
    });

    $('body').on('keypress', '.mtmultiline', function(event) {
        var text = $(this).val();

        var len = text.length;
        var lines = text.split("\n");
        var currentLine = this.value.substr(0, this.selectionStart).split("\n").length;

        limits = getLimits(event.currentTarget);

        if (!isValidLimits(limits)) {
            return;
        }

        var rows = limits[0];
        var cols = limits[1];
        var is72field = false;

        if ($(this).data('textarea-type') == '72') {
            is72field = true;

            if (len == 0) {
                $(this).val(text + '/');
            }

        }

        if (event.keyCode == 13) {
            if (lines.length >= rows) {
                return false;
            } else {
                if (is72field) {
                    $(this).val(text + "\n" + '/');
           
                    return false;
                }
            }
        } else {
            if (lines[currentLine - 1].length >= cols) {
                if (lines.length <= rows - 1) {
                    if (is72field) {
                        $(this).val(text + "\n" + '//');
                    } else {
                        $(this).val(text + "\n");
                    }
                } else {
                    return false;
                }
            }
        }
    });

});