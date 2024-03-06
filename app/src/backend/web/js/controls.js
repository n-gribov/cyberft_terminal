(function() {
  // Burger menu Toggle Script
  $('#toggle').click(function(e) {
    e.preventDefault();
    $('.wrapper').toggleClass('toggled');
  });

  // Make table row to link
  $('.table-dblclick tbody tr').dblclick(function(e) {
        if ($(this).find('a.action-view').length) {
            window.location = $(this).find('a.action-view').attr('href');
        }
  });

  // Collapse accordion in Bootstrap 3 without id's
  $('[data-toggle=next]').click(function(e) {

    $('[data-toggle=next]').not(this).removeClass('collapsed');
    $('[data-toggle=next]').not(this).next().removeClass('in');

    $(this).toggleClass('collapsed');
    $(this).next().collapse('toggle');
  });

  $(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });

  // Хак для корректного вывода ширины блока переключателя с названиями терминалов
  var terminalsNames = $('.brand-dropdown .terminal-name');

  var maxTerminalWidth = 0;

  terminalsNames.each(function() {
      // Получаем название каждого терминала и получаем длину
      var currentLength = $(this).text().length;

      if (currentLength > maxTerminalWidth) {
          maxTerminalWidth = currentLength;
      }
  });

  // Примерное количество пикселей на один символ
  var pixelPerSymbol = 10;

  // Примерная погрешность для блока
  var caretWidth = 20;

  // Если вложенных элементов нет, то не изменяем ширину блока выпадающего меню
  if (maxTerminalWidth !== 0 && terminalsNames.length > 1) {

      // Полученную максимальную длину названия умножаем на количество пикселей
      var dropdownWidth = maxTerminalWidth * pixelPerSymbol + caretWidth;

      // Устанавливаем ширину для блока с выпадающим меню
      $('.brand-dropdown').css('min-width', dropdownWidth + 'px');
  }
})();

function deprecateSpaceSymbol(elem) {
    // Запрет ввода пробела в качестве первого символа
    $('body').on('keypress', elem, function(e) {
        if (this.selectionStart === 0 && e.keyCode === 32) {
            return false;
        }
    });

    $('body').on('paste', elem, function(e) {
        var self = this;

        setTimeout(function(e) {
            $(elem).val($(self).val().replace(/^\s+/g, ''));
        }, 0);
    });
}

// Редирект пользователя, если он был неактивен
function checkIdle() {
    window.App.minuteCount++;

    if (window.App.minuteCount >= window.App.userLogoutTimeout) {
        clearInterval(window.App.checkingInterval);
        location.href = '/site/redirect-if-user-inactive';
    }
}

if (window.App !== undefined && window.App.userLogoutTimeout !== undefined) {
    window.App.minuteCount = 0;
    window.App.checkingInterval = setInterval(checkIdle, 60000); // проверка раз в минуту
    $(document).on('keypress click', function() { window.App.minuteCount = 0; });
}

function parseQueryString(queryString) {
    if (!queryString) {
        queryString = window.location.search;
    }

    queryString = queryString.replace('?', '');

    var params = {}, queries, temp, i, l;
    queries = queryString.split('&');
    for ( i = 0, l = queries.length; i < l; i++ ) {
        temp = queries[i].split('=');
        params[temp[0]] = temp[1];
    }

    return params;
}
