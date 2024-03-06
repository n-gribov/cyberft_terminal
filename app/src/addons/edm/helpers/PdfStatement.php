<?php

namespace addons\edm\helpers;

use common\document\Document;
use kartik\mpdf\Pdf;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use setasign\Fpdi\PdfParser\StreamReader;
use Yii;

final class PdfStatement
{
    /**
     * @var Document
     */
    private $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function renderSummary(): string
    {
        return $this->renderPdf(
            Pdf::ORIENT_LANDSCAPE,
            Yii::getAlias('@backend/web/css/edm/statement/style.css'),
            Yii::getAlias('@addons/edm/views/documents/printable/statement.php'),
            ['model' => $this->document]
        );
    }

    public function renderOrders(): string
    {
        return $this->renderPdf(
            Pdf::ORIENT_PORTRAIT,
            Yii::getAlias('@backend/web/css/edm/paymentOrder/style-pdf.css'),
            Yii::getAlias('@addons/edm/views/documents/printable/statementAll.php'),
            ['model' => $this->document, 'savePdf' => true]
        );
    }

    public function renderAll(): string
    {
        $summaryPdf = $this->renderSummary();
        $ordersPdf = $this->renderOrders();
        return $this->mergePdf($summaryPdf, $ordersPdf);
    }

    private function renderHtml(string $templatePath, array $params): string
    {
        Yii::setAlias('@webroot/assets', Yii::getAlias('@backend/assets'));
        Yii::setAlias('@web/assets', Yii::getAlias('@backend/assets'));
        return Yii::$app->view->renderFile($templatePath, $params);
    }

    private function renderPdf(string $orientation, string $cssPath, string $htmlTemplatePath, array $templateParams): string
    {
        $htmlContent = $this->renderHtml($htmlTemplatePath, $templateParams);
        $pdf = new Pdf([
            'mode'        => Pdf::MODE_UTF8,
            'format'      => Pdf::FORMAT_A4,
            'orientation' => $orientation,
            'destination' => Pdf::DEST_STRING,
            'cssInline'   => file_get_contents($cssPath),
            'content'     => $htmlContent,
        ]);
        return $pdf->render();
    }

    private function mergePdf(string ...$parts): string
    {
        $mpdf = new Mpdf(['mode' => Pdf::MODE_UTF8]);
        foreach ($parts as $i => $part) {
            $pagesInFile = $mpdf->setSourceFile(StreamReader::createByString($part));
            $isLastDocument = $i + 1 === count($parts);
            for ($pageNumber = 1; $pageNumber <= $pagesInFile; $pageNumber++) {
                $pageId = $mpdf->importPage($pageNumber);
                $mpdf->useTemplate($pageId, 0, 0, null, null, true);
                $hasMorePages = !$isLastDocument || $pageNumber < $pagesInFile;
                if ($hasMorePages) {
                    $mpdf->writeHTML('<pagebreak/>');
                }
            }
        }
        return $mpdf->output(null, Destination::STRING_RETURN);
    }
}
