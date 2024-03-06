var wikiArticleSelect = $('#select-wiki-article');
var wikiSectionSelect = $('#select-wiki-section');
var wikiArticleBody = $('.article-body');
var wikiArticleBLock = $('.select-article-block');

// Обработка записи выбранного значения статьи
$('#save-selected-article').on('click', function(e) {

    // Получаем id виджета
    var widgetId = $(this).data('widget-id');

    var articleId = wikiArticleSelect.find(":selected").val();
    var sectionId = wikiSectionSelect.find(":selected").val();

    // Сохраняем ссылку на раздел, если не указана конкретная статья
    if (articleId) {
        pageId = articleId;
    } else {
        pageId = sectionId;
    }

    $.ajax({
        url: '/wiki/set-widget-article',
        type: 'get',
        data: 'widgetId=' + widgetId + '&pageId=' + pageId,
        success: function(result){

            // Получаем ответ
            result = JSON.parse(result);

            if (result.status === 'ok') {
                // Если успешно, то перезагружаем страницу
                window.location.reload();
            } else if (result.status === 'error') {
                // Если есть ошибка, выводим её
                alert(result.msg);
            }
        }
    });
});

// Обработка выбора раздела статьи
function changeSection() {

    // Получаем выбранное из списка разделов значение
    var sectionId = wikiSectionSelect.find(":selected").val();

    // Очищаем текущий выбор в списке статей
    wikiArticleSelect.select2("val", "");

    // Если очистили список выбора раздела,
    if (!sectionId) {
        wikiArticleBLock.slideUp();
        return false;
    }

    // Получение информации по разделу и всех вложенных статей
    $.ajax({
        url: '/wiki/get-section',
        type: 'get',
        data: 'sectionId=' + sectionId,
        success: function(result){
            // Получаем ответ
            result = JSON.parse(result);

            // Удаляем существующие элементы списка
            wikiArticleSelect.empty();
            wikiArticleSelect.append('<option></option>');

            // Если есть вложенные статьи, отображаем их
            if (result.articles.length > 0) {
                // Формируем разметку выбора статей
                html = "";

                result.articles.forEach(function(item) {
                    html += "<option value='" + item.id + "'>" + item.title + "</option>";
                });

                wikiArticleSelect.append(html);

                // Отображаем блок выбора статьи
                wikiArticleBLock.slideDown();
            } else {
                // Скрываем блок выбора статьи
                wikiArticleBLock.slideUp();

                // Отображаем блок с текстом раздела
                wikiArticleBody.html(result.body);
                wikiArticleBody.slideDown();
            }
        }
    });
}

// Обработка выбора статьи
function changeArticle() {
    // Получаем выбранное из списка разделов значение
    var articleId = wikiArticleSelect.find(":selected").val();

    // Если очистили список выбора статьи
    if (!articleId) {
        // Очищаем область просмотра статьи
        wikiArticleBody.slideUp();
        wikiArticleBody.html('');
        return false;
    }

    // Получаем и устанавливаем содержимое указанной статьи
    setArticleBody(articleId);
}

function setArticleBody(articleId) {
    $.ajax({
        url: '/wiki/get-article',
        type: 'get',
        data: 'articleId=' + articleId,
        success: function(result){

            // Получаем ответ
            result = JSON.parse(result);

            // Загружаем статью в поле просмотра
            wikiArticleBody.html(result.body);
            wikiArticleBody.slideDown();
        }
    });
}

$('#wikiModal').on('show.bs.modal', function () {
    var articleId = $('#wikiModal').data('article-id');

    if (articleId) {
        setArticleBody(articleId);
    } else {
        wikiArticleBody.html("");
    }
});

// Инициализация
function init() {
    // Отображаем поле выбора статьи, если заполнен раздел
    var sectionId = '$sectionId';
    var articleId = '$articleId';

    if (sectionId) {
        wikiArticleBody.show();

        if (articleId) {
            wikiArticleBLock.show();
        }
    }
}

// Инициализация js
init();