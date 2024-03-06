<?php
namespace common\states\out;

use common\base\BaseDocumentState;
use common\document\Document;
use Yii;

class SendingState extends BaseDocumentState
{
    protected $_steps = [
        'autoSign' => 'common\states\out\DocumentAutoSignStep',
        'sign' => 'common\states\out\DocumentSignStep',
        'addHistory' => 'common\states\out\DocumentAddHistoryStep',
        'compress' => 'common\states\out\DocumentCompressStep',
        'encrypt' => 'common\states\out\DocumentEncryptStep',
        'analyze' => 'common\states\out\DocumentAnalyzeStep',
        'decide' => null, // step decider
    ];

    protected $_retrySteps = [
        'decide' => [
            'attempts' => 3,
            'interval' => 60
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

        if (empty($this->cyxDoc)) {
            $this->cyxDoc = $this->document->getCyberXml();
        }

        if (!$this->terminalId) {
            $this->terminalId = $this->document->sender;
        }

        if (!$this->status) {
            $this->status = array_keys($this->_steps)[0];
        }

        if (!Yii::$app->exchange->isRunning($this->terminalId)) {
            $this->log('Terminal ' . $this->terminalId . ' is not running. Saving state.');

            $this->save();

            return false;
        }

        if (!$this->module ) {
             $this->module = Yii::$app->registry->getTypeModule($this->document->type);
        }

        return parent::run();
    }

    protected function decideStep()
    {
        switch($this->document->status) {
            case Document::STATUS_SENDING_FAIL:
            case Document::STATUS_FORSENDING: return 'common\states\out\DocumentSendStep';

            case Document::STATUS_UPLOAD_FAIL:
            case Document::STATUS_UPLOADED:
            case Document::STATUS_REQUEST_FAIL:
            case Document::STATUS_FORUPLOADING: return 'common\states\out\DocumentUploadStep';
        }

        return null;
    }

}