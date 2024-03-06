<?php

namespace common\jobs;

use common\document\Document;

class DocumentSignJob extends BaseDocumentSignJob
{
    protected function injectSignature()
    {
        if (!$this->_cyxDoc->injectSignature($this->_signature, $this->_certBody)) {
            return false;
        }

        if (!$this->_cyxDoc->verify($this->_certBody)) {
            $this->_document->updateStatus(Document::STATUS_FORSIGNING);

            return false;
        }

        return true;
    }

}
