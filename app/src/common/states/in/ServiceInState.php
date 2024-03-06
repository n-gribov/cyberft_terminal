<?php
namespace common\states\in;

use common\base\BaseDocumentState;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use Yii;

class ServiceInState extends BaseDocumentState
{
    public $storedFileId;

    protected $_steps = [
        'validate' => 'common\states\in\DocumentValidateStep',
        'verify' => 'common\states\in\DocumentServiceVerifyStep',
        'process' => 'common\states\in\ServiceProcessStep',
        'export' => 'common\states\in\ServiceExportStep',
    ];

    public function run()
    {
        if (empty($this->cyxDoc)) {
            $this->cyxDoc = CyberXmlDocument::read($this->storedFileId);
            if (empty($this->cyxDoc)) {
                $this->log('Failed to create CyberXmlDocument from stored file ' . $this->storedFileId);

                return false;
            }
        }

        if (empty($this->document)) {
            $this->document = Document::findOne($this->documentId);
            if (empty($this->document)) {
                $this->log('Failed to find document id ' . $this->documentId);

                return false;
            }
        }

        if (empty($this->module)) {
            $this->module = Yii::$app->registry->getTypeModule($this->document->type);
        }
	
        return parent::run();
    }

}
