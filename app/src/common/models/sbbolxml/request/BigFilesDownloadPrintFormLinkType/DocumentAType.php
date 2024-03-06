<?php

namespace common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType;

/**
 * Class representing DocumentAType
 */
class DocumentAType
{

    /**
     * Глобальный UUID документа СББОЛ, по которому нужно
     *  сформировать печатную форму(Не указывается для выписки)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Gets as docId
     *
     * Глобальный UUID документа СББОЛ, по которому нужно
     *  сформировать печатную форму(Не указывается для выписки)
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
     * Глобальный UUID документа СББОЛ, по которому нужно
     *  сформировать печатную форму(Не указывается для выписки)
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

