<?php

namespace common\models\sbbolxml\request\CertifRequestType\DocsAType;

/**
 * Class representing DocAType
 */
class DocAType
{

    /**
     * Тип запроса, передается константа:
     *  «sign» - для запроса сертификата для подписи,
     *  «tls» - для запроса сертификата tls.
     *  При необходимости список может быть расширен.
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Отдельное вложение
     *
     * @property \common\models\sbbolxml\request\CertifRequestType\DocsAType\DocAType\AttachmentAType $attachment
     */
    private $attachment = null;

    /**
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ;
     *  последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @property \common\models\sbbolxml\request\ParamType[] $params
     */
    private $params = null;

    /**
     * Gets as type
     *
     * Тип запроса, передается константа:
     *  «sign» - для запроса сертификата для подписи,
     *  «tls» - для запроса сертификата tls.
     *  При необходимости список может быть расширен.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип запроса, передается константа:
     *  «sign» - для запроса сертификата для подписи,
     *  «tls» - для запроса сертификата tls.
     *  При необходимости список может быть расширен.
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as attachment
     *
     * Отдельное вложение
     *
     * @return \common\models\sbbolxml\request\CertifRequestType\DocsAType\DocAType\AttachmentAType
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Sets a new attachment
     *
     * Отдельное вложение
     *
     * @param \common\models\sbbolxml\request\CertifRequestType\DocsAType\DocAType\AttachmentAType $attachment
     * @return static
     */
    public function setAttachment(\common\models\sbbolxml\request\CertifRequestType\DocsAType\DocAType\AttachmentAType $attachment)
    {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * Adds as param
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ;
     *  последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @return static
     * @param \common\models\sbbolxml\request\ParamType $param
     */
    public function addToParams(\common\models\sbbolxml\request\ParamType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ;
     *  последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetParams($index)
    {
        return isset($this->params[$index]);
    }

    /**
     * unset params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ;
     *  последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetParams($index)
    {
        unset($this->params[$index]);
    }

    /**
     * Gets as params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ;
     *  последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @return \common\models\sbbolxml\request\ParamType[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets a new params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ;
     *  последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @param \common\models\sbbolxml\request\ParamType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }


}

