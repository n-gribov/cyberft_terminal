<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ConfDocCertificateDoc181IType
 *
 *
 * XSD Type: ConfDocCertificateDoc181I
 */
class ConfDocCertificateDoc181IType extends ConfDocCertificateDoc138IType
{

    /**
     * Дата корректируемой СПД
     *
     * @property \DateTime $adjustmentDate
     */
    private $adjustmentDate = null;

    /**
     * Gets as adjustmentDate
     *
     * Дата корректируемой СПД
     *
     * @return \DateTime
     */
    public function getAdjustmentDate()
    {
        return $this->adjustmentDate;
    }

    /**
     * Sets a new adjustmentDate
     *
     * Дата корректируемой СПД
     *
     * @param \DateTime $adjustmentDate
     * @return static
     */
    public function setAdjustmentDate(\DateTime $adjustmentDate)
    {
        $this->adjustmentDate = $adjustmentDate;
        return $this;
    }


}

