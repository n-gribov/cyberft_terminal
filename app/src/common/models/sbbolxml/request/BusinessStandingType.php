<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BusinessStandingType
 *
 * Сведения о деловой репутации
 * XSD Type: BusinessStanding
 */
class BusinessStandingType
{

    /**
     * Отзыв(ы) в произвольной письменной форме других клиентов ОАО «Сбербанк России»
     *
     * @property boolean $writingReferencesFromSBRFClients
     */
    private $writingReferencesFromSBRFClients = null;

    /**
     * Отзыв(ы) в произвольной письменной форме от других кредитных организаций
     *
     * @property boolean $writingReferencesFromCredOrgs
     */
    private $writingReferencesFromCredOrgs = null;

    /**
     * Отзыв(ы) не предоставлены
     *
     * @property boolean $noReferencesGiven
     */
    private $noReferencesGiven = null;

    /**
     * Gets as writingReferencesFromSBRFClients
     *
     * Отзыв(ы) в произвольной письменной форме других клиентов ОАО «Сбербанк России»
     *
     * @return boolean
     */
    public function getWritingReferencesFromSBRFClients()
    {
        return $this->writingReferencesFromSBRFClients;
    }

    /**
     * Sets a new writingReferencesFromSBRFClients
     *
     * Отзыв(ы) в произвольной письменной форме других клиентов ОАО «Сбербанк России»
     *
     * @param boolean $writingReferencesFromSBRFClients
     * @return static
     */
    public function setWritingReferencesFromSBRFClients($writingReferencesFromSBRFClients)
    {
        $this->writingReferencesFromSBRFClients = $writingReferencesFromSBRFClients;
        return $this;
    }

    /**
     * Gets as writingReferencesFromCredOrgs
     *
     * Отзыв(ы) в произвольной письменной форме от других кредитных организаций
     *
     * @return boolean
     */
    public function getWritingReferencesFromCredOrgs()
    {
        return $this->writingReferencesFromCredOrgs;
    }

    /**
     * Sets a new writingReferencesFromCredOrgs
     *
     * Отзыв(ы) в произвольной письменной форме от других кредитных организаций
     *
     * @param boolean $writingReferencesFromCredOrgs
     * @return static
     */
    public function setWritingReferencesFromCredOrgs($writingReferencesFromCredOrgs)
    {
        $this->writingReferencesFromCredOrgs = $writingReferencesFromCredOrgs;
        return $this;
    }

    /**
     * Gets as noReferencesGiven
     *
     * Отзыв(ы) не предоставлены
     *
     * @return boolean
     */
    public function getNoReferencesGiven()
    {
        return $this->noReferencesGiven;
    }

    /**
     * Sets a new noReferencesGiven
     *
     * Отзыв(ы) не предоставлены
     *
     * @param boolean $noReferencesGiven
     * @return static
     */
    public function setNoReferencesGiven($noReferencesGiven)
    {
        $this->noReferencesGiven = $noReferencesGiven;
        return $this;
    }


}

