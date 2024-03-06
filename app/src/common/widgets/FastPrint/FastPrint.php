<?php

namespace common\widgets\FastPrint;

use yii\base\Widget;

/*
 * Виджет для вызова окна печати какой-либо страницы
 */
class FastPrint extends Widget
{
    public $printUrl;
    public $printBtn;
    public $documentId;
    public $documentType;

    public function run()
    {
        if ($this->printUrl && $this->printBtn) {
            // Вывести страницу
            echo $this->render('index', [
                'printUrl' => $this->printUrl,
                'printBtn' => $this->printBtn,
                'iframeHash' => md5($this->printUrl),
                'documentId' => $this->documentId,
                'documentType' => $this->documentType
            ]);
        }
    }
}
