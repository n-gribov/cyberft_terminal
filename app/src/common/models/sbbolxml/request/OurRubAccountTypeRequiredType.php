<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing OurRubAccountTypeRequiredType
 *
 *
 * XSD Type: OurRubAccountTypeRequired
 */
class OurRubAccountTypeRequiredType extends AccountRubType
{

    /**
     * 0 - Средства в рублях перечислить на наш счет в Сбербанке России
     *  1 - Средства в рублях перечислить на счет в другом банке / в других филиалах и отделениях Сбербанка России
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Дополнительные реквизиты банка
     *
     * @property string $additionalRequisite
     */
    private $additionalRequisite = null;

    /**
     * Gets as type
     *
     * 0 - Средства в рублях перечислить на наш счет в Сбербанке России
     *  1 - Средства в рублях перечислить на счет в другом банке / в других филиалах и отделениях Сбербанка России
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
     * 0 - Средства в рублях перечислить на наш счет в Сбербанке России
     *  1 - Средства в рублях перечислить на счет в другом банке / в других филиалах и отделениях Сбербанка России
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
     * Gets as additionalRequisite
     *
     * Дополнительные реквизиты банка
     *
     * @return string
     */
    public function getAdditionalRequisite()
    {
        return $this->additionalRequisite;
    }

    /**
     * Sets a new additionalRequisite
     *
     * Дополнительные реквизиты банка
     *
     * @param string $additionalRequisite
     * @return static
     */
    public function setAdditionalRequisite($additionalRequisite)
    {
        $this->additionalRequisite = $additionalRequisite;
        return $this;
    }


}

