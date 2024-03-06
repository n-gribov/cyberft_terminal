<?php

namespace addons\edm\models\BaseSBBOLDocument;

abstract class BaseSBBOLRequestDocumentCyberXmlContent extends BaseSBBOLDocumentCyberXmlContent
{
    public function boundAttributes()
    {
        return [
            'request' => '//sbbol:Document',
            'digest'  => '//sbbol:Digest',
        ];
    }

    public function pushRequest()
    {
        $requestXml = (string)$this->_typeModel;
        $this->pushAttribute('Document', $requestXml);
    }

    public function pushDigest()
    {
        $this->pushAttribute('Digest', $this->_typeModel->digest);
    }

    public function fetchRequest()
    {
        $this->fetchAttribute('request');
    }
}
