<?php
namespace common\states\out;

use common\base\BaseDocumentState;
use common\document\Document;
use Yii;

class DocumentOutState extends BaseDocumentState
{
    protected $_steps = [
        'create' => 'common\states\out\DocumentCreateStep',
        'extModelCreate' => 'common\states\ExtModelCreateStep',
        'process' => 'common\states\out\DocumentProcessStep'
    ];

    public function decideState()
    {
        if ($this->document->status == Document::STATUS_ACCEPTED) {
            return new SendingState();
        }

        /**
         * Если документ не ACCEPTED, то слать его пока не нужно, новый стейт не порождаем
         */
        return null;
    }

    protected function getDefaultApiImportErrorMessage(): ?string
    {
        return Yii::t('document', 'Failed to send document');
    }
}
