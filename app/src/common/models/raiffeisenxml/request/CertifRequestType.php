<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing CertifRequestType
 *
 *
 * XSD Type: CertifRequest
 */
class CertifRequestType
{

    /**
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @property \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType[] $docs
     */
    private $docs = null;

    /**
     * Adds as doc
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType $doc
     */
    public function addToDocs(\common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType $doc)
    {
        $this->docs[] = $doc;
        return $this;
    }

    /**
     * isset docs
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDocs($index)
    {
        return isset($this->docs[$index]);
    }

    /**
     * unset docs
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDocs($index)
    {
        unset($this->docs[$index]);
    }

    /**
     * Gets as docs
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @return \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType[]
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     * Sets a new docs
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @param \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType[] $docs
     * @return static
     */
    public function setDocs(array $docs)
    {
        $this->docs = $docs;
        return $this;
    }


}

