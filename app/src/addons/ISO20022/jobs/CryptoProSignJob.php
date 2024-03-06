<?php

namespace addons\ISO20022\jobs;

use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\helpers\RosbankHelper;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\base\BaseBlock;
use common\base\Job;
use common\document\Document;
use common\helpers\CryptoProHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Resque_Job_DontPerform;
use Yii;

class CryptoProSignJob extends Job
{
    /** @var BaseBlock $_module */
	private $_module;

	/** @var Document */
    private $_document;

    /** @var CyberXmlDocument */
    private $_cyxDoc;

	public function setUp()
	{
        parent::setUp();

        if (isset($this->args['id'])) {
            $this->_document = Document::findOne($this->args['id']);
            if (is_null($this->_document)) {
                $this->log('Document id ' . $this->args['id'] . ' not found');

                throw new \Resque_Job_DontPerform();
            }
        } else {
            $this->log('Document id must be set');

            throw new Resque_Job_DontPerform();
        }

        $this->_module = Yii::$app->registry->getTypeModule($this->_document->type);
        if (!$this->_module) {
            $this->log('Module not found for type ' . $this->_document->type);

            throw new Resque_Job_DontPerform();
        }
	}

	public function perform()
	{
        try {
            $document = $this->_document;
            $extModel = $document->extModel;
            $this->_cyxDoc = $this->_document->getCyberXml();
            $typeModel = $this->_cyxDoc->getContent()->getTypeModel();

            if ($this->_module->settings['enableCryptoProSign']) {
                $isRosbank = RosbankHelper::isGatewayTerminal($this->_document->receiver);
                $isSigned = $isRosbank
                    ? RosbankHelper::signDocument($this->_document, $typeModel)
                    : CryptoProHelper::sign($this->_module->getServiceId(), $typeModel);
                if (!$isSigned) {
                    $extModel->extStatus = ISO20022DocumentExt::STATUS_CRYPTOPRO_SIGNING_ERROR;
                    $extModel->save();
                    $document->updateStatus(Document::STATUS_PROCESSING_ERROR);

                    Yii::$app->monitoring->log('document:CryptoProSigningError', 'document', $document->id, [
                        'terminalId' => $document->terminalId
                    ]);

                    $document->save();

                    throw new \Resque_Job_DontPerform('CryptoProHelper::sign(ISO20022) failed');
                }

                $extModel->extStatus = ISO20022DocumentExt::STATUS_CRYPTOPRO_SIGNING_SUCCESS;
                $extModel->save();

                $this->modifyStoredFile($typeModel);
                $this->log($typeModel->type . ' ' . $document->id . ' signed with cryptopro keys');
            } else {
                $extModel->extStatus = '';
                $extModel->save();
            }
            DocumentTransportHelper::processDocument($document, true);
        } catch(Exception $ex) {
            $this->log($ex->getMessage(), true);
        }
	}

    private function modifyStoredFile($typeModel)
    {
        ISO20022Helper::updateZipContent($typeModel);

        /**
         * @todo: заменить на cyxDoc->setTypeModel()
         */
        $docId = $this->_cyxDoc->docId;
        $docDate = $this->_cyxDoc->docDate;
        $senderId = $this->_cyxDoc->senderId;
        $receiverId = $this->_cyxDoc->receiverId;

        $newCyxDoc = CyberXmlDocument::loadTypeModel($typeModel);
        $newCyxDoc->docId = $docId;
        $newCyxDoc->docDate = $docDate;
        $newCyxDoc->senderId = $senderId;
        $newCyxDoc->receiverId = $receiverId;

        $storedFile = Yii::$app->storage->get($this->_document->actualStoredFileId);
        $storedFile->updateData($newCyxDoc->saveXML());
    }

}