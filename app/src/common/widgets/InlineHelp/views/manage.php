<?php

use kartik\select2\Select2;

?>

<div class="clearfix select-block select-section-block">
    <div class="col-md-10">
        <?php
        echo Select2::widget([
            'id' => 'select-wiki-section',
            'name' => 'select-wiki-section',
            'options' => [
                'placeholder' => 'Выберите раздел'
            ],
            'data' => $sections,
            'value' => $sectionId,
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents' => [
                "change" => "function() { changeSection() }",
            ]
        ]);
        ?>
    </div>
    <div class="col-md-2">
        <a id="save-selected-article" href="#" class="btn btn-primary add-article-button" data-widget-id="<?=$widgetId?>">Применить</a>
    </div>
</div>

<div class="clearfix select-block select-article-block">
    <div class="col-md-12">
        <?php
        echo Select2::widget([
            'id' => 'select-wiki-article',
            'name' => 'select-wiki-article',
            'options' => [
                'placeholder' => 'Выберите статью'
            ],
            'data' => $articles,
            'value' => $articleId,
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents' => [
                "change" => "function() { changeArticle() }",
            ]
        ]);
        ?>
    </div>
</div>