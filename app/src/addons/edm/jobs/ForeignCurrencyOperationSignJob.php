<?php

namespace addons\edm\jobs;

use common\base\BaseType;
use common\base\interfaces\BlockInterface;
use common\base\Job;
use common\components\storage\StoredFile;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Resque_Job_DontPerform;
use Yii;

class ForeignCurrencyOperationSignJob extends Job
{
    private $_documentId;
    private $_signature;
    private $_certBody;

    /**
     * @var Document
     */
    private $_document;

    /**
     * @var StoredFile
     */
    private $_storedFile;

    /**
     * @var BaseType
     */
    private $_typeModel;

    /**
     * @var BlockInterface
     */
    private $_module;

    /**
     * @var CyberXmlDocument
     */
    private $_cyxDoc;

    public function setUp()
    {
        parent::setUp();

        if (!$this->checkArgs()) {
            if ($this->_document->status == Document::STATUS_SIGNING) {
                $this->_document->status = Document::STATUS_FORSIGNING;
                $this->_document->save(false, ['status']);
            }

            throw new Resque_Job_DontPerform('Invalid job configuration');
        }
    }

    public function perform()
    {
        try {
            if ($this->_document->status != Document::STATUS_SIGNING) {
                $this->_document->status = Document::STATUS_SIGNING;
                $this->_document->save(false, ['status']);
            }

            if ($this->_typeModel->type == 'MT103' || $this->_typeModel->type == 'pain.001') {
                $result = $this->_cyxDoc->injectSignature($this->_signature, $this->_certBody);
            } else {
                $result = $this->_typeModel->injectSignature($this->_signature, $this->_certBody);
            }

            if ($result) {
                // inject modified type model into cyberxml
                if ($this->_typeModel->type != 'MT103' && $this->_typeModel->type != 'pain.001') {
                    $this->_cyxDoc->setTypeModel($this->_typeModel);
                }

                $this->_storedFile->updateData($this->_cyxDoc->saveXML());
                $this->_document->signaturesCount++;

                // Здесь надо поставить дефолтный STATUS_FORSIGNING,
                // т.к. в джоб модель попала в нестабильном статусе STATUS_SIGNING,
                // который нарушил бы логику isSignable

                $this->_document->status = Document::STATUS_FORSIGNING;
                $this->_document->save(false, ['signaturesCount', 'status']);
                // модуль должен решить, что делать дальше
                $this->_module->processDocument($this->_document);

                // Если документ приобрел статус STATUS_ACCEPTED, произойдет его отправка
                DocumentTransportHelper::processDocument($this->_document);
            } else {
                 $this->log('Unable to sign ForeignCurrencyOperation ' . $this->_typeModel->id);
            }
        } catch (Exception $ex) {
            Yii::info($ex->getMessage(). PHP_EOL . $ex->getTraceAsString());
        }

        return;
    }

    /**
     * Check input arguments
     *
     * @return boolean
     */
    private function checkArgs()
    {
        $this->_documentId = (isset($this->args['id'])) ? $this->args['id'] : null;
        if (empty($this->_documentId)) {
            $this->log('Document ID is empty');

            return false;
        }

        $this->_document = Document::findOne(['id' => $this->_documentId]);
        if (empty($this->_document)) {
            $this->log('Get document model error');

            return false;
        }

        $this->_signature = (isset($this->args['signature'])) ? $this->args['signature'] : null;
        if (empty($this->_signature)) {
            $this->log('Signature is empty');

            return false;
        }

        $this->_certBody = (isset($this->args['certBody'])) ? $this->args['certBody'] : null;
        if (empty($this->_certBody)) {
            $this->log('Cert body is empty');

            return false;
        }

        $this->_storedFile = Yii::$app->storage->get($this->_document->actualStoredFileId);

        if (empty($this->_storedFile)) {
            $this->log('Get StoredFile error');

            return false;
        }

        $this->_cyxDoc = new CyberXmlDocument();
        $this->_cyxDoc->loadXML($this->_storedFile->getData());

        $this->_typeModel = $this->_cyxDoc->getContent()->getTypeModel();
        if (empty($this->_typeModel)) {
            $this->log('Get ForeignCurrencyOperationType error');

            return false;
        }

        $this->_module = Yii::$app->registry->getTypeModule($this->_typeModel->gettype());
        if (empty($this->_module)) {
            $this->log('Module for docType ' . $this->_typeModel->getType() . ' not found');

            return false;
        }

        return true;
    }
}
