<?php

namespace common\models\sbbolxml\response\StatementType\OutdatedDocsAType;

/**
 * Class representing TransInfoAType
 */
class TransInfoAType
{

    /**
     * Заполняется идентификатором документа в ЕКС, который был удален
     *  из выписки (перешедший в ACC_OUTDATED_OPERATIONS/OPERATIONID из ACCOUNT_OPERATIONS/OPERATIONID)
     *
     * @property string $docAbcId
     */
    private $docAbcId = null;

    /**
     * Gets as docAbcId
     *
     * Заполняется идентификатором документа в ЕКС, который был удален
     *  из выписки (перешедший в ACC_OUTDATED_OPERATIONS/OPERATIONID из ACCOUNT_OPERATIONS/OPERATIONID)
     *
     * @return string
     */
    public function getDocAbcId()
    {
        return $this->docAbcId;
    }

    /**
     * Sets a new docAbcId
     *
     * Заполняется идентификатором документа в ЕКС, который был удален
     *  из выписки (перешедший в ACC_OUTDATED_OPERATIONS/OPERATIONID из ACCOUNT_OPERATIONS/OPERATIONID)
     *
     * @param string $docAbcId
     * @return static
     */
    public function setDocAbcId($docAbcId)
    {
        $this->docAbcId = $docAbcId;
        return $this;
    }


}

