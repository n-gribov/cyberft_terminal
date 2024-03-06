<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing Payer50Type
 *
 *
 * XSD Type: Payer_50
 */
class Payer50Type
{

    /**
     * Номер счёта и БИК перевододателя
     *  Customer Account
     *
     * @property \common\models\raiffeisenxml\request\AccNumBicType $accDoc
     */
    private $accDoc = null;

    /**
     * Международное наименование плательщика
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Адрес плательщика
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Gets as accDoc
     *
     * Номер счёта и БИК перевододателя
     *  Customer Account
     *
     * @return \common\models\raiffeisenxml\request\AccNumBicType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Номер счёта и БИК перевододателя
     *  Customer Account
     *
     * @param \common\models\raiffeisenxml\request\AccNumBicType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\raiffeisenxml\request\AccNumBicType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as name
     *
     * Международное наименование плательщика
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Международное наименование плательщика
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as address
     *
     * Адрес плательщика
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * Адрес плательщика
     *
     * @param string $address
     * @return static
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }


}

