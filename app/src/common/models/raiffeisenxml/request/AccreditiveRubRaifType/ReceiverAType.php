<?php

namespace common\models\raiffeisenxml\request\AccreditiveRubRaifType;

/**
 * Class representing ReceiverAType
 */
class ReceiverAType
{

    /**
     * Физическое лицо
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\IndividualAType $individual
     */
    private $individual = null;

    /**
     * Юридическое лицо/ИП
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\LegalEntityAType $legalEntity
     */
    private $legalEntity = null;

    /**
     * Счет получателя.
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\AccountAType $account
     */
    private $account = null;

    /**
     * Gets as individual
     *
     * Физическое лицо
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\IndividualAType
     */
    public function getIndividual()
    {
        return $this->individual;
    }

    /**
     * Sets a new individual
     *
     * Физическое лицо
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\IndividualAType $individual
     * @return static
     */
    public function setIndividual(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\IndividualAType $individual)
    {
        $this->individual = $individual;
        return $this;
    }

    /**
     * Gets as legalEntity
     *
     * Юридическое лицо/ИП
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\LegalEntityAType
     */
    public function getLegalEntity()
    {
        return $this->legalEntity;
    }

    /**
     * Sets a new legalEntity
     *
     * Юридическое лицо/ИП
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\LegalEntityAType $legalEntity
     * @return static
     */
    public function setLegalEntity(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\LegalEntityAType $legalEntity)
    {
        $this->legalEntity = $legalEntity;
        return $this;
    }

    /**
     * Gets as account
     *
     * Счет получателя.
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\AccountAType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Счет получателя.
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\AccountAType $account
     * @return static
     */
    public function setAccount(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType\AccountAType $account)
    {
        $this->account = $account;
        return $this;
    }


}

