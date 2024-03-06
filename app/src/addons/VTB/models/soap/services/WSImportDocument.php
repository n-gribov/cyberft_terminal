<?php

namespace addons\VTB\models\soap\services;

use addons\VTB\models\soap\messages\WSImportDocument\ImportDocumentRequest;
use addons\VTB\models\soap\messages\WSImportDocument\ImportDocumentResponse;

class WSImportDocument extends BaseService
{
    /**
     * @param ImportDocumentRequest $request
     * @return ImportDocumentResponse
     */
    public function importDocument(ImportDocumentRequest $request)
    {
        return $this->execute('ImportDocument', $request);
    }

}
