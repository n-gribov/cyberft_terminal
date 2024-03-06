<?php

namespace addons\edm\models\BaseSBBOLDocument;

abstract class BaseSBBOLResponseDocumentCyberXmlContent extends BaseSBBOLDocumentCyberXmlContent
{
    public function boundAttributes()
    {
        return [
            'response' => '//sbbol:Document',
        ];
    }

    public function pushResponse()
    {
        $requestXml = (string)$this->_typeModel;
        $this->pushAttribute('Document', $requestXml);
    }

    public function fetchResponse()
    {
        $this->fetchAttribute('response');
    }
}
