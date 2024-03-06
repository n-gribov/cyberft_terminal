/*

 Author - Rudolf Naprstek
 Website - http://www.thimbleopensource.com/tutorials-snippets/jquery-plugin-filter-text-input
 Version - 1.5.3
 Release - 12th February 2014

 Thanks to Niko Halink from ARGH!media for bugfix!
 Remy Blom: Added a callback function when the filter surpresses a keypress in order to give user feedback
 Don Myers: Added extension for using predefined filter masks
 Richard Eddy: Added extension for using negative number
 */

(function($){
    $.fn.extend({
        filter_input: function(options) {
            var defaults = {
                regex: '.',
                negkey: false, // use "-" if you want to allow minus sign at the beginning of the string
                live: false,
                events: 'keypress paste',
                casing: undefined
            };

            var options =  $.extend(defaults, options);

            function filter_input_function(event) {
                var input = (event.input) ? event.input : $(this);
                if (event.ctrlKey || event.altKey) return;
                if (event.type=='keypress') {
                    var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
                    if (
                        // 8 = backspace, 9 = tab, 13 = enter, 35 = end, 36 = home, 37 = left, 39 = right, 46 = delete
                        key == 8 || key == 9 || key == 13 || key == 35 || key == 36 || key == 37 || key == 39 || key == 46
                        // 38 = up arrow, 40 = down arrow, 33 = page up, 34 = page down
                        || key == 38 || key == 40 || key == 33 || key == 34
                    ) {
                        return true;
                    }

                    var string = String.fromCharCode(key);
                    // @todo реализовать модификацию регистра вводимого символа
                    // if they pressed the defined negative key
                    if (options.negkey && string == options.negkey) {
                        // if there is already one at the beginning, remove it
                        if (input.val().substr(0, 1) == string) {
                            input.val(input.val().substring(1, input.val().length)).change();
                        } else {
                            // it isn't there so add it to the beginning of the string
                            input.val(string + input.val()).change();
                        }
                        return false;
                    }
                    var regex = new RegExp(options.regex);
                } else if (event.type=='paste') {
                    input.data('value_before_paste', event.target.value);
                    setTimeout(function(){
                        filter_input_function({type:'after_paste', input:input});
                    }, 100);
                    return true;
                } else if (event.type=='after_paste') {
                    var string = input.val();
                    if($(input)[0].nodeName == 'TEXTAREA') {
                        var regex = new RegExp('^('+options.regex+')+$', 'm');
                    } else {
                        var regex = new RegExp('^('+options.regex+')+$');
                    }
                } else {
                    return false;
                }

                if (regex.test(string)) {
                    return true;
                } else if (typeof(options.feedback) == 'function') {
                    options.feedback.call(this, string);
                }
                if (event.type=='after_paste') input.val(input.data('value_before_paste'));

                $(input).removeClass('error').addClass('error');
                clearTimeout(blinkId);
                var blinkId = setTimeout(function() {
                    $(input).removeClass('error');
                }, 700);

                return false;
            }

            var jquery_version = $.fn.jquery.split('.');
            if (options.live) {
                if (parseInt(jquery_version[0]) >= 1 && parseInt(jquery_version[1]) >= 7) {
                    $(this).on(options.events, filter_input_function);
                } else {
                    $(this).live(options.events, filter_input_function);
                }
            } else {
                return this.each(function() {
                    var input = $(this);
                    if (parseInt(jquery_version[0]) >= 1 && parseInt(jquery_version[1]) >= 7) {
                        input.off(options.events).on(options.events, filter_input_function);
                    } else {
                        input.unbind(options.events).bind(options.events, filter_input_function);
                    }
                });
            }
        }
    });
})(jQuery);

jQuery(document).ready(function () {
    jQuery('input[data-filter], textarea[data-filter]').each(function (idx) {
        var regex = jQuery(this).data('filter');
        var casing = jQuery(this).data('casing');
        jQuery(this).filter_input({regex: regex, casing: casing});
    });
});

/**
 *  Author - Don Myers
 *  Version - 0.1.0
 *  Release - March 1st 2013
 */

/*
 use any of these filters or regular expression in some cases the regular expression is shorter but for some people the "names" might be easier

 ie.
 <input type="text" name="first_name" value="" data-mask="[a-zA-Z ]" placeholder="eg. John"/>
 <input type="text" name="last_name" value="" data-mask="int" placeholder="eg. Smith"/>

 */

/*
 jQuery(document).ready(function() {

 var masks = {
 'int': Â  Â  /[\d]/,
 'float': Â  Â  /[\d\.]/,
 'money': Â  Â /[\d\.\s,]/,
 'num': Â  Â  Â /[\d\-\.]/,
 'hex': Â  Â  Â /[0-9a-f]/i,
 'email': Â  Â /[a-z0-9_\.\-@]/i,
 'alpha': Â  Â /[a-z_]/i,
 'alphanum': /[a-z0-9_]/i,
 'alphanumlower':/[a-z0-9_]/,
 'alphaspace': Â  Â /[a-z ]/i,
 'alphanumspace': /[a-z0-9_ ]/i,
 'alphanumspacelower':/[a-z0-9_ ]/
 };

 jQuery('input[data-mask]').each(function(idx) {
 var mask = jQuery(this).data('mask');
 var regex = (masks[mask]) ? masks[mask] : mask;

 jQuery(this).filter_input({ regex: regex, live: true });Â
 });
 });

 */
