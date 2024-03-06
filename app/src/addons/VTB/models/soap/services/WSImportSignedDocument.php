<?php

namespace addons\VTB\models\soap\services;

use addons\VTB\models\soap\messages\WSImportSignedDocument\ImportSignedDocumentRequest;
use addons\VTB\models\soap\messages\WSImportSignedDocument\ImportSignedDocumentResponse;

class WSImportSignedDocument extends BaseService
{
    /**
     * @param ImportSignedDocumentRequest $request
     * @return ImportSignedDocumentResponse
     */
    public function importSignedDocument(ImportSignedDocumentRequest $request)
    {
        return $this->execute('ImportSignedDocument', $request);
    }

}
