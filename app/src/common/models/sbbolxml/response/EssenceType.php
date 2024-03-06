<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing EssenceType
 *
 * Сущность ГОЗ со ссылками
 * XSD Type: Essence
 */
class EssenceType
{

    /**
     * идентификатор сущности в СББОЛ
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Тип сущности: SUBST_DOC - подтверждающий документ, ACT - акт, CONTRACT - контракт
     *
     * @property string $essenceType
     */
    private $essenceType = null;

    /**
     * @property \common\models\sbbolxml\response\LinkToBigFileType[] $link
     */
    private $link = array(
        
    );

    /**
     * Gets as docId
     *
     * идентификатор сущности в СББОЛ
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * идентификатор сущности в СББОЛ
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as essenceType
     *
     * Тип сущности: SUBST_DOC - подтверждающий документ, ACT - акт, CONTRACT - контракт
     *
     * @return string
     */
    public function getEssenceType()
    {
        return $this->essenceType;
    }

    /**
     * Sets a new essenceType
     *
     * Тип сущности: SUBST_DOC - подтверждающий документ, ACT - акт, CONTRACT - контракт
     *
     * @param string $essenceType
     * @return static
     */
    public function setEssenceType($essenceType)
    {
        $this->essenceType = $essenceType;
        return $this;
    }

    /**
     * Adds as link
     *
     * @return static
     * @param \common\models\sbbolxml\response\LinkToBigFileType $link
     */
    public function addToLink(\common\models\sbbolxml\response\LinkToBigFileType $link)
    {
        $this->link[] = $link;
        return $this;
    }

    /**
     * isset link
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetLink($index)
    {
        return isset($this->link[$index]);
    }

    /**
     * unset link
     *
     * @param scalar $index
     * @return void
     */
    public function unsetLink($index)
    {
        unset($this->link[$index]);
    }

    /**
     * Gets as link
     *
     * @return \common\models\sbbolxml\response\LinkToBigFileType[]
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets a new link
     *
     * @param \common\models\sbbolxml\response\LinkToBigFileType[] $link
     * @return static
     */
    public function setLink(array $link)
    {
        $this->link = $link;
        return $this;
    }


}

