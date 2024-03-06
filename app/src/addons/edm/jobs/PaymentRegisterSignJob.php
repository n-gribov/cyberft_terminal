<?php

namespace addons\edm\jobs;

use addons\edm\models\PaymentRegister\PaymentRegisterType;
use common\base\Job;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Resque_Job_DontPerform;
use Yii;

class PaymentRegisterSignJob extends Job
{
    private $_module;

    private $_documentId;
    private $_signature;
    private $_certBody;
    private $_document;
    private $_cyxDoc;

    private $_typeModel;
    private $_storedFile;
    private $_message;

    public function setUp()
    {
        parent::setUp();

        if (!$this->checkArgs()) {
            if ($this->_document->status == Document::STATUS_SIGNING) {
                $this->_document->status = Document::STATUS_FOR_SIGNING;
                $this->_document->save(false, ['status']);
            }

            $this->log('PaymentRegister signing job invalid configuration: ' . $this->_message, true);

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

            if ($this->_typeModel->injectSignature($this->_signature, $this->_certBody)) {

                $this->_cyxDoc->setTypeModel($this->_typeModel);

                if (is_null($this->_storedFile->updateData($this->_cyxDoc->saveXML()))) {
                    $this->log('PaymentRegister was signed but could not save storedFile');

                    return;
                }

                $this->_document->signaturesCount++;

                // Здесь надо поставить дефолтный STATUS_FORSIGNING,
                // т.к. в джоб модель попала в нестабильном статусе STATUS_SIGNING,
                // который нарушил бы логику isSignable

                $this->_document->status = Document::STATUS_FORSIGNING;
                $this->_document->save(false, ['status', 'signaturesCount']);

                // модуль должен решить, что делать дальше
                $this->_module->processDocument($this->_document);

                // Если документ приобрел статус STATUS_ACCEPTED, произойдет его отправка
                DocumentTransportHelper::processDocument($this->_document);

            } else {
                 $this->log('Unable to sign PaymentRegister ' . $this->_typeModel->id);
            }
        } catch (Exception $e) {
            Yii::info($e->getMessage(). PHP_EOL . $e->getTraceAsString());
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
        $this->_module = Yii::$app->registry->getTypeModule(PaymentRegisterType::TYPE);
        if (empty($this->_module)) {
            $this->_message = 'Module for docType ' . $this->_typeModel->getType() . ' not found';

            return false;
        }

        $this->_documentId = (isset($this->args['id'])) ? $this->args['id'] : null;
        if (empty($this->_documentId)) {
            $this->_message = 'Document ID is empty';

            return false;
        }

        $this->_certBody = (isset($this->args['certBody'])) ? $this->args['certBody'] : null;
        if (empty($this->_certBody)) {
            $this->_message = 'Cert body is empty';

            return false;
        }

        $this->_signature = (isset($this->args['signature'])) ? $this->args['signature'] : null;
        if (empty($this->_signature)) {
            $this->_message = 'Signature is empty';

            return false;
        }

        $this->_document = Document::findOne($this->_documentId);
        if (empty($this->_document)) {
            $this->_message = 'Document ' . $this->_documentId . ' not found';

            return false;
        }

        $this->_storedFile = Yii::$app->storage->get($this->_document->actualStoredFileId);
        if (!$this->_storedFile) {
            $this->_message = 'Stored file ' . $this->_document->actualStoredFileId . ' is invalid';

            return false;
        }

        $this->_cyxDoc = new CyberXmlDocument();
        $this->_cyxDoc->loadXml($this->_storedFile->getData());

        $this->_typeModel = $this->_cyxDoc->getContent()->getTypeModel();
        if (empty($this->_typeModel)) {
            $this->_message = 'Get PaymentOrderType error';

            return false;
        }

        return true;
    }

}