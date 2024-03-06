<?php

namespace common\models\raiffeisenxml\request\CertifRequestType;

/**
 * Class representing DocsAType
 */
class DocsAType
{

    /**
     * Содержит данные одного запроса сертификата, которые создаются по в
     *  рамках одного клиенского запроса (запрос сертификата для подписи/запрос сертификата
     *  TLS и т.д.)
     *
     * @property \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType[] $doc
     */
    private $doc = [
        
    ];

    /**
     * Adds as doc
     *
     * Содержит данные одного запроса сертификата, которые создаются по в
     *  рамках одного клиенского запроса (запрос сертификата для подписи/запрос сертификата
     *  TLS и т.д.)
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType $doc
     */
    public function addToDoc(\common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType $doc)
    {
        $this->doc[] = $doc;
        return $this;
    }

    /**
     * isset doc
     *
     * Содержит данные одного запроса сертификата, которые создаются по в
     *  рамках одного клиенского запроса (запрос сертификата для подписи/запрос сертификата
     *  TLS и т.д.)
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDoc($index)
    {
        return isset($this->doc[$index]);
    }

    /**
     * unset doc
     *
     * Содержит данные одного запроса сертификата, которые создаются по в
     *  рамках одного клиенского запроса (запрос сертификата для подписи/запрос сертификата
     *  TLS и т.д.)
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDoc($index)
    {
        unset($this->doc[$index]);
    }

    /**
     * Gets as doc
     *
     * Содержит данные одного запроса сертификата, которые создаются по в
     *  рамках одного клиенского запроса (запрос сертификата для подписи/запрос сертификата
     *  TLS и т.д.)
     *
     * @return \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType[]
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * Sets a new doc
     *
     * Содержит данные одного запроса сертификата, которые создаются по в
     *  рамках одного клиенского запроса (запрос сертификата для подписи/запрос сертификата
     *  TLS и т.д.)
     *
     * @param \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType[] $doc
     * @return static
     */
    public function setDoc(array $doc)
    {
        $this->doc = $doc;
        return $this;
    }


}
