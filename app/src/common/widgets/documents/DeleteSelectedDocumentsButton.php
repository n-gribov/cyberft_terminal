<?php


namespace common\widgets\documents;

use yii\base\Widget;

class DeleteSelectedDocumentsButton extends Widget
{
    public $checkboxesSelector;
    public $deleteUrl = 'delete';
    public $entriesSelectionCacheKey;

    public function run()
    {
        return $this->render(
            'deleteSelectedDocumentsButton',
            [
                'checkboxesSelector' => $this->checkboxesSelector,
                'deleteUrl' => $this->deleteUrl,
                'entriesSelectionCacheKey' => $this->entriesSelectionCacheKey,
            ]
        );
    }
}
