<?php
namespace common\states\out;

use common\base\BaseDocumentState;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use Yii;

class ServiceOutState extends BaseDocumentState
{
    public $refDocId;

    protected $_steps = [
        'create' => 'common\states\out\DocumentCreateStep',
        'send' => 'common\states\out\DocumentSendStep',
    ];

    protected $_retrySteps = [
        'send' => [
            'attempts' => 3,
            'interval' => 5
        ]
    ];

    public function transfer(BaseDocumentState $oldState)
    {
        if (!empty($oldState->refDocId)) {
            $this->refDocId = $oldState->refDocId;
        }

        parent::transfer($oldState);
    }

    public function run()
    {
        if ($this->status) {
            if (empty($this->cyxDoc) || empty($this->document)) {
                $this->document = Document::findOne($this->documentId);

                if (!$this->document) {
                    $this->log('Document id ' . $this->documentId . ' not found');

                    return false;
                }

                $this->cyxDoc = CyberXmlDocument::read($this->document->actualStoredFileId);
            }

            if (!$this->terminalId) {
                $this->terminalId = $this->document->sender;
            }

            if (!$this->status) {
                $this->status = array_keys($this->_steps)[0];
            }

            if (!Yii::$app->terminals->isRunning($this->terminalId)) {
                $this->log('Terminal ' . $this->terminalId . ' is not running. Saving state.');

                $this->save();

                return false;
            }

            if (!$this->module ) {
                 $this->module = Yii::$app->registry->getTypeModule($this->document->type);
            }
        }

        return parent::run();
    }

    public function __construct($params = null)
    {
        parent::__construct($params);

        $this->module = Yii::$app->getModule('transport');
    }

}