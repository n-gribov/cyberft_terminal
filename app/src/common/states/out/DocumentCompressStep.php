<?php
namespace common\states\out;

use common\base\DOMCyberXml;
use common\document\Document;
use common\helpers\ZipHelper;
use common\states\BaseDocumentStep;
use DOMXPath;
use Exception;
use Yii;

class DocumentCompressStep extends BaseDocumentStep
{
    const INITIAL_INCOMING_STATE = Document::STATUS_MAIN_AUTOSIGNED;
	const PROCESSING_STATE = Document::STATUS_COMPRESSING;
	const SUCCESSFUL_PROCESSED_STATE = Document::STATUS_COMPRESSED;
	const ERRONEOUS_PROCESSED_STATE = Document::STATUS_COMPRESSION_ERROR;

    public $name = 'compress';

    public function run()
    {
        $document = $this->state->document;
        $document->updateStatus(self::PROCESSING_STATE);

        if (
            // За каким-то хером StatusReport попадает в стейт с шагом упаковки,
            // у него контент-модель не содержит методов для работы с зипом,
            // надо это разрулить или оставить такой костыль
            $document->type == \common\modules\transport\models\StatusReportType::TYPE
            || !Yii::$app->settings->get('app', $this->state->document->sender)->useZipBeforeEncrypt
        ) {
            $document->updateStatus(self::SUCCESSFUL_PROCESSED_STATE);

            return true;
        }

        $cyxDoc = $this->state->cyxDoc;

        try {
            $dom = $cyxDoc->getDom();
            $xpath = new DOMXPath($dom);
            $xpath->registerNamespace('doc', 'http://cyberft.ru/xsd/cftdoc.01');
            $xpathQuery = '/doc:Document/doc:Body';

            $nodeset = $xpath->query($xpathQuery);
            if (!$nodeset->length) {
                throw new Exception('Failed to obtain Body node');
            }

            $node = $nodeset->item(0);

            /**
             * Проверка по старому стилю (cftdata.01)
             * Если Body имеет mimeType zip, то скипаем
             */
            if ($node->getAttribute('mimeType') == 'application/zip') {

                $document->updateStatus(self::SUCCESSFUL_PROCESSED_STATE);

                return true;
            }

            /**
             * Проверка по новому стилю (cftdata.02)
             * Если Body имеет RawData с mimeType zip, то скипаем
             */
            $children = $node->childNodes;
            foreach($children as $child) {
                if (
                    $child->nodeType == XML_ELEMENT_NODE
                    && $child->tagName == 'RawData'
                    && $child->getAttribute('mimeType') == 'application/zip'
                ) {
                    $document->updateStatus(self::SUCCESSFUL_PROCESSED_STATE);

                    return true;
                }
            }

            $content = $node->ownerDocument->saveXML($node);

            /**
             * Вырезается для упаковки все, что между <Body>...</Body>
             */
            $pos = strpos($content, '>');

            if ($pos === false || substr($content, -7) !== '</Body>') {
                throw new Exception('Failed to parse Body tag in content');
            }

            /**
             * Сохраняется тэг Body с оригинальными атрибутами для повторной вставки
             */
            $bodyPrefix = substr($content, 0, $pos + 1);

            $content = substr($content, $pos + 1, strlen($content) - 8 - $pos);

            /**
             * Формируется новый Body c упакованным RawData
             */
            $zipData = $bodyPrefix
                    . '<RawData xmlns="http://cyberft.ru/xsd/cftdata.02" encoding="base64" mimeType="application/zip" purpose="transport">'
                    . base64_encode(ZipHelper::packStringToString($content))
                    . '</RawData></Body>';

            DOMCyberXml::replaceChild($node, $zipData);

            $this->state->document->updateStatus(self::SUCCESSFUL_PROCESSED_STATE);

        } catch(Exception $ex) {
            $this->log($ex);
            $document->updateStatus(self::ERRONEOUS_PROCESSED_STATE);

            return false;
        }

        return true;
    }

}