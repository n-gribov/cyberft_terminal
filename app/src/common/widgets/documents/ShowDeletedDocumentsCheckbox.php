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
        return $this->render('showDeletedDocumentsCheckbox', ['filterModel' => $this->filterModel]);
    }
}
