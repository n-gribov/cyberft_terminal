<?php


namespace common\widgets\documents;

use yii\base\Widget;

class SelectedDocumentsCountLabel extends Widget
{
    public $checkboxesSelector = '';
    public $initialCount = 0;

    public function run()
    {
        return $this->render(
            // Вывести количество помеченных документов
            'selectedDocumentsCountLabel',
            [
                'checkboxesSelector' => $this->checkboxesSelector,
                'initialCount'       => $this->initialCount
            ]
        );
    }
}
