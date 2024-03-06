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
        return $this->render('transport-info-modal', [
            'document' => $this->document, 'isVolatile' => $this->isVolatile, 'errorDescription' => $this->errorDescription
        ]);
    }

}
