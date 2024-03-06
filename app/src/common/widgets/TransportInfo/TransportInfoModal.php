<?php

namespace common\widgets\TransportInfo;

use yii\base\Widget;

class TransportInfoModal extends Widget
{
    public $document;
    public $isVolatile;
    public $errorDescription;

    public function run()
    {
        // Вывести модальное окно с транспортной информацией
        return $this->render('transport-info-modal', [
            'document' => $this->document, 'isVolatile' => $this->isVolatile, 'errorDescription' => $this->errorDescription
        ]);
    }
}
