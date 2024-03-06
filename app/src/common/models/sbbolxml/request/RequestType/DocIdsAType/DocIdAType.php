<?php

namespace common\models\sbbolxml\request\RequestType\DocIdsAType;

/**
 * Class representing DocIdAType
 */
class DocIdAType
{

    /**
     * Тикет СББОЛ (UUID документа)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Идентификатор документа в УС.
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Формат получения ответа Response/Tickets. По умолчанию используется значение Full.
     *  Подробное описание версии каждого формата(и возможность его использования) содержится в каждом отдельном документе.
     *
     * @property string $format
     */
    private $format = null;

    /**
     * Gets as docId
     *
     * Тикет СББОЛ (UUID документа)
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
     * Тикет СББОЛ (UUID документа)
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в УС.
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в УС.
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as format
     *
     * Формат получения ответа Response/Tickets. По умолчанию используется значение Full.
     *  Подробное описание версии каждого формата(и возможность его использования) содержится в каждом отдельном документе.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Sets a new format
     *
     * Формат получения ответа Response/Tickets. По умолчанию используется значение Full.
     *  Подробное описание версии каждого формата(и возможность его использования) содержится в каждом отдельном документе.
     *
     * @param string $format
     * @return static
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }


}

