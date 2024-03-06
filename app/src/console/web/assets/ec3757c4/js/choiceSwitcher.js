/**
 * Функция переключает опции в формах создания MT-документов
 * Устанавливается как обработчик события нажатие на переключатель - radio
 */
optionSwitcher = (function (e, radioGroupName) {
	if (e) { // Обработка события - нажатие на радиоэлемент управления
		// Атрибут для связывания с группой полей составного объекта (data-bind)
		var optionName = $(e.target).data('bind');
		// Значение нажатой опции
		var myActiveRadioValue = $(e.target).val();
		// Получаем список всех связанных видимых групп полей
		var myVisibleDivs = $("div[data-bind^='" + optionName + "']").filter(":visible");
		// Если только одна видимая группа - это переключение групп полей
		if (myVisibleDivs.length == 1) {
			// Не переключаем, если нажали на уже активную опцию
			if (myVisibleDivs.data('bind') == optionName + myActiveRadioValue)
				return false;
			// Ищем все поля связанные с текущей опцией
			var myInputs = $("div[data-bind^=" + myVisibleDivs.attr('data-bind') + "] *").filter(":input");
			// Проверяем наличие введенных в поля данных
			var changedCnt = 0;
			myInputs.each(function () {
				if ($(this).val() != '')
					changedCnt++;
			});
			// Предупреждаем о потере данных в полях
			if (changedCnt > 0) {
				if (confirm('Поля уже заполнены. Желаете переключить опцию?') == false)
					return false; // Не переключаем опции
			}
			// Очищаем поля перед переключением
			//myInputs.each(function () {
			//	$(this).val('');
			//});
		}
		myVisibleDivs.hide(); // Скрываем текущую видимую групп(у|ы) полей
		// Делаем видимой группу полей, связанную с нажатой опцией
		$("div[data-bind='" + optionName + myActiveRadioValue + "']").show();
	}
	return true;
});

/**
 * Функция, выполняющая начальную инициализацию переключателей опций в формах
 * создания MT-документов.
 * Исходно на форме все группы полей для опций видимы. Необходимо определить,
 * какие группы необходимо скрыть.
 */
optionSwitcherInit = (function () {
	// Начальная инициализация 
	// Название текущей опции
	var myActiveRadioValue = $(this).val();
	// Атрибут для связывания с группой полей составного объекта (data-bind)
	var optionName = $(this).data('bind');
	// Получаем список всех связанных видимых групп полей, который д.б.
	// скрыты при инициализации
	var myVisibleDivs =
		$("div[data-bind^='" + optionName + "'][data-bind!='" + optionName + myActiveRadioValue + "']")
			.filter(":visible");
	myVisibleDivs.hide(); // Прячем все ненужные группы
	return true;
});

(function () {
	// Устанавливаем обработчики событий для каждого из переключателей опций, !!! в том числе и не существующие на странице
	$('body').on('click', ".mt-choice-switcher", [], optionSwitcher);

	var myRadioGroups = $(".mt-choice-switcher");
	// Получаем все активные переключатели
	var myCheckedRadios = myRadioGroups.filter(":checked");
	// Инициализируем каждую из групп, которым принадлежат активные переключатели
	if (myCheckedRadios.length > 0) {
		myRadioGroups.filter(":checked").each(optionSwitcherInit);
	} else {
		// Если нет активных элементов в группах, то  в каждой группе 
		// активируем первый переключатель, имитируя нажатие на него
		do {
			var myCurrentRadio = myRadioGroups.first();
			myCurrentRadio.click();
			// Удаляем из списка все остальные элементы обработанной группы
			var optionName = myCurrentRadio.data('bind');
			myRadioGroups = myRadioGroups.not("[data-bind='" + optionName + "']");
		} while (myRadioGroups.length > 0);
	}
})();


$(document).ready(function()
{
    $("select[data-master] > option:selected").each(function () {
        if ($(this).val() != '')
            $("div[data-bind='" + $(this).attr('data-bind') + "']").show();
    });

    $('body').on('change', 'select[data-master]', function() {
        parent = $(this).parent().parent().children("div[data-container='" + $(this).attr('data-master') + "']");

        parent.children('div[data-bind]').hide();
        parent.children("#" + $(this).find('option:selected').attr('data-bind') ).show();
    });
});