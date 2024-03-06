<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing IntCtrlStatementXML181IType
 *
 * Запрос о получении данных по ведомости банковского контроля
 * XSD Type: IntCtrlStatementXML181I
 */
class IntCtrlStatementXML181IType
{

    /**
     * Идентификатор документа в СББОЛ (UUID документа)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Gets as docId
     *
     * Идентификатор документа в СББОЛ (UUID документа)
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор документа в СББОЛ (UUID документа)
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }


}

