<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BigFilesDownloadPrintFormLinkType
 *
 * Запрос на создание печатной формы выписки на Банке
 * XSD Type: BigFilesDownloadPrintFormLinkType
 */
class BigFilesDownloadPrintFormLinkType
{

    /**
     * Расширение получаемого файла: pdf, rtf, docx, html, excel
     *
     * @property string $extension
     */
    private $extension = null;

    /**
     * Печатная форма выписки
     *
     * @property \common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType\StatementAType $statement
     */
    private $statement = null;

    /**
     * Печатная форма документа
     *
     * @property \common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType\DocumentAType $document
     */
    private $document = null;

    /**
     * Gets as extension
     *
     * Расширение получаемого файла: pdf, rtf, docx, html, excel
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Sets a new extension
     *
     * Расширение получаемого файла: pdf, rtf, docx, html, excel
     *
     * @param string $extension
     * @return static
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * Gets as statement
     *
     * Печатная форма выписки
     *
     * @return \common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType\StatementAType
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * Sets a new statement
     *
     * Печатная форма выписки
     *
     * @param \common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType\StatementAType $statement
     * @return static
     */
    public function setStatement(\common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType\StatementAType $statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * Gets as document
     *
     * Печатная форма документа
     *
     * @return \common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType\DocumentAType
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Sets a new document
     *
     * Печатная форма документа
     *
     * @param \common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType\DocumentAType $document
     * @return static
     */
    public function setDocument(\common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType\DocumentAType $document)
    {
        $this->document = $document;
        return $this;
    }


}

