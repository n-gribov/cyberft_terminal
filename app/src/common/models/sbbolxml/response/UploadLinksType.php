<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing UploadLinksType
 *
 * Ссылки на закачку в систему БФ
 * XSD Type: UploadLinks
 */
class UploadLinksType
{

    /**
     * Ссылка на закачку в систему БФ
     *
     * @property \common\models\sbbolxml\response\UploadLinkType[] $uploadLink
     */
    private $uploadLink = array(
        
    );

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Adds as uploadLink
     *
     * Ссылка на закачку в систему БФ
     *
     * @return static
     * @param \common\models\sbbolxml\response\UploadLinkType $uploadLink
     */
    public function addToUploadLink(\common\models\sbbolxml\response\UploadLinkType $uploadLink)
    {
        $this->uploadLink[] = $uploadLink;
        return $this;
    }

    /**
     * isset uploadLink
     *
     * Ссылка на закачку в систему БФ
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetUploadLink($index)
    {
        return isset($this->uploadLink[$index]);
    }

    /**
     * unset uploadLink
     *
     * Ссылка на закачку в систему БФ
     *
     * @param scalar $index
     * @return void
     */
    public function unsetUploadLink($index)
    {
        unset($this->uploadLink[$index]);
    }

    /**
     * Gets as uploadLink
     *
     * Ссылка на закачку в систему БФ
     *
     * @return \common\models\sbbolxml\response\UploadLinkType[]
     */
    public function getUploadLink()
    {
        return $this->uploadLink;
    }

    /**
     * Sets a new uploadLink
     *
     * Ссылка на закачку в систему БФ
     *
     * @param \common\models\sbbolxml\response\UploadLinkType[] $uploadLink
     * @return static
     */
    public function setUploadLink(array $uploadLink)
    {
        $this->uploadLink = $uploadLink;
        return $this;
    }

    /**
     * Gets as sign
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

