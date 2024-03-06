<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing EnableUseSignCorrDictType
 *
 *
 * XSD Type: EnableUseSignCorrDict
 */
class EnableUseSignCorrDictType
{

    /**
     * использовать подтвержденный справочник контрагентов
     *  да - 1
     *  нет - 0
     *
     * @property boolean $active
     */
    private $active = null;

    /**
     * Gets as active
     *
     * использовать подтвержденный справочник контрагентов
     *  да - 1
     *  нет - 0
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Sets a new active
     *
     * использовать подтвержденный справочник контрагентов
     *  да - 1
     *  нет - 0
     *
     * @param boolean $active
     * @return static
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }


}

