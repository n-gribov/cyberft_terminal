<?php

namespace common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType;

/**
 * Class representing DocsAType
 */
class DocsAType
{

    /**
     * Доступны все типы
     *  документов.
     *  1- признак установлен;
     *  0- признак НЕ установлен.
     *
     * @property boolean $all
     */
    private $all = null;

    /**
     * Доступные типы
     *  документов
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType\DocAType[] $doc
     */
    private $doc = array(
        
    );

    /**
     * Gets as all
     *
     * Доступны все типы
     *  документов.
     *  1- признак установлен;
     *  0- признак НЕ установлен.
     *
     * @return boolean
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * Sets a new all
     *
     * Доступны все типы
     *  документов.
     *  1- признак установлен;
     *  0- признак НЕ установлен.
     *
     * @param boolean $all
     * @return static
     */
    public function setAll($all)
    {
        $this->all = $all;
        return $this;
    }

    /**
     * Adds as doc
     *
     * Доступные типы
     *  документов
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType\DocAType $doc
     */
    public function addToDoc(\common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType\DocAType $doc)
    {
        $this->doc[] = $doc;
        return $this;
    }

    /**
     * isset doc
     *
     * Доступные типы
     *  документов
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDoc($index)
    {
        return isset($this->doc[$index]);
    }

    /**
     * unset doc
     *
     * Доступные типы
     *  документов
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDoc($index)
    {
        unset($this->doc[$index]);
    }

    /**
     * Gets as doc
     *
     * Доступные типы
     *  документов
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType\DocAType[]
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * Sets a new doc
     *
     * Доступные типы
     *  документов
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType\DocAType[] $doc
     * @return static
     */
    public function setDoc(array $doc)
    {
        $this->doc = $doc;
        return $this;
    }


}

