<?php

namespace common\widgets\documents;

use yii\base\Widget;

class SelectedDocumentsCache extends Widget
{
    public $saveUrl;

    public function run()
    {
        return $this->render('selectedDocumentsCache', ['saveUrl' => $this->saveUrl,]);
    }
}
