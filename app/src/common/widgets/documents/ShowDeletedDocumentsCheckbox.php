<?php

namespace common\widgets\documents;

use common\document\DocumentSearch;
use yii\base\Widget;

class ShowDeletedDocumentsCheckbox extends Widget
{
    /** @var DocumentSearch */
    public $filterModel;

    public function run()
    {
        // Вывести чекбокс показа удалённых документов
        return $this->render('showDeletedDocumentsCheckbox', ['filterModel' => $this->filterModel]);
    }
}
