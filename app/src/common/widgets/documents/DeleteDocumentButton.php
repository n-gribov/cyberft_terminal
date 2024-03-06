<?php


namespace common\widgets\documents;

use yii\base\Widget;

class DeleteDocumentButton extends Widget
{
    public $documentId;
    public $deleteUrl = 'delete';

    public function run()
    {
        // Вывести кнопку удаления документа
        return $this->render(
            'deleteDocumentButton',
            [
                'documentId' => $this->documentId,
                'deleteUrl' => $this->deleteUrl,
            ]
        );
    }
}
