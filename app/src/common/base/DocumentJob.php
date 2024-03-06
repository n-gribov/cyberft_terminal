<?php
namespace common\base;

use common\base\Job;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use Resque_Job_DontPerform;
use yii\base\Module;

abstract class DocumentJob extends Job
{
    /**
     * @var integer $_documentId Document ID
     */
    protected $_documentId;

    /**
     * @var Document $_document Document
     */
    protected $_document;

    /**
     * @var CyberXmlDocument $_cyxDocument CyberXml document
     */
    protected $_cyxDocument;

    /**
     * @var Module $_module Module
     */
    protected $_module;

    protected $docInfo;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        if (!$this->checkArgs() || !$this->_module) {
            throw new Resque_Job_DontPerform('Bad arguments');
        }
    }

    protected function checkArgs()
    {
        $this->_documentId = (!empty($this->args['documentId'])) ? $this->args['documentId'] : null;

        if (empty($this->_documentId)) {
            $this->log('Document ID is empty', true);

            return false;
        }

        $this->_document = Document::findOne($this->_documentId);
        if (empty($this->_document)) {
            $this->log("Document ID {$this->_documentId} not found", true);

            return false;
        }

        $this->docInfo = $this->_document->type . ' ' . $this->_documentId;

        $this->_cyxDocument = CyberXmlDocument::read($this->_document->actualStoredFileId);
        if (empty($this->_cyxDocument)) {
            $this->log('Could not create CyberXml document from storage ID ' . $this->_document->actualStoredFileId, true);

            return false;
        }

        return true;
    }

}