<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;

// Если пользователь НЕ админ и статья не найдена, формируем простую ссылку
//  с уведомлением о том, что статья недоступна
if ($error && !$canManage) {
    echo Html::a('', '#', [
        'title' => 'Статья по данному элементу недоступна',
        'class' => $classList
    ]);
} else {
    echo Html::a('', '#', [
        'data' => [
            'toggle' => 'modal',
            'target' => '#wikiModal'
        ],
        'title' => $articleTitle,
        'class' => $classList
    ]);
}

$header = '<h4 class="modal-title">' . $articleTitle . '</h4>';

$modal = Modal::begin([
    'id' => 'wikiModal',
    'header' => $header,
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'tabindex' => false,
        'data' => [
            'article-id' => $articleId
        ]
    ]
]);

// Блок изменения привязки к статье отображется только для пользователей с нужными правами
if ($canManage) {
    echo $this->render('manage', compact(
        'sections', 'sectionId',
        'articles', 'articleId',
        'widgetId'
    ));
}
?>
<div class="clearfix">
    <div class="col-md-12">
        <div class="article-body"></div>
    </div>
</div>
<?php
$modal::end();
