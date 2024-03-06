<?php

namespace addons\edm\widgets;

use addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestForm;
use addons\edm\services\VTBDocumentCancellationService;
use yii\base\Widget;

class CancelVtbDocumentButton extends Widget
{
    public $document;
    public $documentNumber;
    public $documentDate;

    /** @var VTBPrepareCancellationRequestForm */
    private $cancellationForm;

    /**
     * @var VTBDocumentCancellationService
     */
    private $cancellationService;

    public function __construct(VTBDocumentCancellationService $cancellationService, $config = [])
    {
        parent::__construct($config);
        $this->cancellationService = $cancellationService;
    }

    public function init()
    {
        parent::init();
        if ($this->document) {
            $this->cancellationForm = new VTBPrepareCancellationRequestForm([
                'document' => $this->document,
                'documentNumber' => $this->documentNumber,
                'documentDate' => $this->documentDate,
            ]);
        }
    }

    public function run()
    {
        $documentCanBeCancelled = $this->document !== null && $this->cancellationService->documentCanBeCancelled($this->document);
        return $this->render(
            'cancelVtbDocumentButton',
            [
                'widget' => $this,
                'cancellationForm' => $this->cancellationForm,
                'userCanCancelDocument' => $documentCanBeCancelled,
            ]
        );
    }
}
