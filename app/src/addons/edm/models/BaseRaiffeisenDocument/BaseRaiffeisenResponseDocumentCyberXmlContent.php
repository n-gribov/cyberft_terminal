<?php

namespace addons\edm\models\BaseRaiffeisenDocument;

abstract class BaseRaiffeisenResponseDocumentCyberXmlContent extends BaseRaiffeisenDocumentCyberXmlContent
{
    public function boundAttributes()
    {
        return [
            'response' => '//raiffeisen:Document',
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
