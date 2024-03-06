<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing SalaryDocsAType
 */
class SalaryDocsAType
{

    /**
     * Электронный реестр (Зарплатная ведомость)
     *
     * @property \common\models\sbbolxml\response\RZKSalaryDocType[] $salaryDoc
     */
    private $salaryDoc = array(
        
    );

    /**
     * Adds as salaryDoc
     *
     * Электронный реестр (Зарплатная ведомость)
     *
     * @return static
     * @param \common\models\sbbolxml\response\RZKSalaryDocType $salaryDoc
     */
    public function addToSalaryDoc(\common\models\sbbolxml\response\RZKSalaryDocType $salaryDoc)
    {
        $this->salaryDoc[] = $salaryDoc;
        return $this;
    }

    /**
     * isset salaryDoc
     *
     * Электронный реестр (Зарплатная ведомость)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSalaryDoc($index)
    {
        return isset($this->salaryDoc[$index]);
    }

    /**
     * unset salaryDoc
     *
     * Электронный реестр (Зарплатная ведомость)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSalaryDoc($index)
    {
        unset($this->salaryDoc[$index]);
    }

    /**
     * Gets as salaryDoc
     *
     * Электронный реестр (Зарплатная ведомость)
     *
     * @return \common\models\sbbolxml\response\RZKSalaryDocType[]
     */
    public function getSalaryDoc()
    {
        return $this->salaryDoc;
    }

    /**
     * Sets a new salaryDoc
     *
     * Электронный реестр (Зарплатная ведомость)
     *
     * @param \common\models\sbbolxml\response\RZKSalaryDocType[] $salaryDoc
     * @return static
     */
    public function setSalaryDoc(array $salaryDoc)
    {
        $this->salaryDoc = $salaryDoc;
        return $this;
    }


}

