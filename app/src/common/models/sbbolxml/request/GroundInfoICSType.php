<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing GroundInfoICSType
 *
 * Сведения о документах, на основании которых должны быть внесены изменения ПС
 * XSD Type: GroundInfoICS
 */
class GroundInfoICSType
{

    /**
     * Другие документы, их реквизиты и информация, необходимые для внесения изменений
     *
     * @property string $groundDocs
     */
    private $groundDocs = null;

    /**
     * Сведения о документе, на основании которого должны быть внесены изменения ПС
     *
     * @property \common\models\sbbolxml\request\GroundICSType[] $ground
     */
    private $ground = array(
        
    );

    /**
     * Gets as groundDocs
     *
     * Другие документы, их реквизиты и информация, необходимые для внесения изменений
     *
     * @return string
     */
    public function getGroundDocs()
    {
        return $this->groundDocs;
    }

    /**
     * Sets a new groundDocs
     *
     * Другие документы, их реквизиты и информация, необходимые для внесения изменений
     *
     * @param string $groundDocs
     * @return static
     */
    public function setGroundDocs($groundDocs)
    {
        $this->groundDocs = $groundDocs;
        return $this;
    }

    /**
     * Adds as ground
     *
     * Сведения о документе, на основании которого должны быть внесены изменения ПС
     *
     * @return static
     * @param \common\models\sbbolxml\request\GroundICSType $ground
     */
    public function addToGround(\common\models\sbbolxml\request\GroundICSType $ground)
    {
        $this->ground[] = $ground;
        return $this;
    }

    /**
     * isset ground
     *
     * Сведения о документе, на основании которого должны быть внесены изменения ПС
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetGround($index)
    {
        return isset($this->ground[$index]);
    }

    /**
     * unset ground
     *
     * Сведения о документе, на основании которого должны быть внесены изменения ПС
     *
     * @param scalar $index
     * @return void
     */
    public function unsetGround($index)
    {
        unset($this->ground[$index]);
    }

    /**
     * Gets as ground
     *
     * Сведения о документе, на основании которого должны быть внесены изменения ПС
     *
     * @return \common\models\sbbolxml\request\GroundICSType[]
     */
    public function getGround()
    {
        return $this->ground;
    }

    /**
     * Sets a new ground
     *
     * Сведения о документе, на основании которого должны быть внесены изменения ПС
     *
     * @param \common\models\sbbolxml\request\GroundICSType[] $ground
     * @return static
     */
    public function setGround(array $ground)
    {
        $this->ground = $ground;
        return $this;
    }


}

