<?php
namespace common\modules\transport\states\in;

use common\base\BaseDocumentState;
use common\document\Document;
use common\states\in\DocumentInState;

class CftcpDownloadState extends BaseDocumentState
{
    protected $_steps = [
        'download' => 'common\modules\transport\states\in\CftcpDownloadStep',
    ];

    protected $_retrySteps = [
        'download' => [
            'attempts' => 3,
            'interval' => 120
        ]
    ];

    public function run()
    {
        if (empty($this->document)) {
            $this->document = Document::findOne($this->documentId);

            if (!$this->document) {
                $this->log('Document id ' . $this->documentId . ' not found');

                return false;
            }
        }

        return parent::run();
    }

    public function decideState()
    {
        return new DocumentInState();
    }

}