<?php

namespace common\states\in;

use common\base\DOMCyberXml;
use common\components\storage\StoredFile;
use common\document\Document;
use common\helpers\ZipHelper;
use common\states\BaseDocumentStep;
use DOMXPath;
use Exception;
use Yii;

class DocumentDecompressStep extends BaseDocumentStep
{
    public $name = 'decompress';

    const INITIAL_INCOMING_STATE = Document::STATUS_DECRYPTED;
	const PROCESSING_STATE = Document::STATUS_DECOMPRESSING;
	const SUCCESSFUL_PROCESSED_STATE = Document::STATUS_DECOMPRESSED;
	const ERRONEOUS_PROCESSED_STATE = Document::STATUS_DECOMPRESSION_ERROR;

    public function run()
    {
        $cyxDoc = $this->state->cyxDoc;
        $this->state->document->updateStatus(static::PROCESSING_STATE);

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
             * Проверка по новому стилю (cftdata.02)
             * Если Body не имеет RawData с mimeType zip и purpose='transport', то скипаем
             */
            $nodes = $node->childNodes;
            $contentNode = null;
            foreach($nodes as $testNode) {
                if (
                    $testNode->nodeType !== XML_ELEMENT_NODE
                    || $testNode->tagName !== 'RawData'
                    || $testNode->getAttribute('mimeType') != 'application/zip'
                    || $testNode->getAttribute('purpose') != 'transport'
                ) {
                    $this->state->document->updateStatus(self::SUCCESSFUL_PROCESSED_STATE);

                    /**
                     * @todo: разобраться, почему без перезагрузки теряется _rootnode при загрузкe контент-модели Fileact
                     */
                    $this->state->cyxDoc->loadXML($dom->saveXML());

                    return true;
                }

                $contentNode = $testNode;

                break;
            }

            $content = ZipHelper::unpackStringFromString(base64_decode($contentNode->nodeValue));

            DOMCyberXml::replaceChild($contentNode, $content);

            $storedFile = StoredFile::findOne($this->state->document->actualStoredFileId);

            if (empty($storedFile)) {
                throw new Exception($this->docInfo . ' failed to find stored file');
            }

            /**
             * Обновить содержимое файла, используя методы ресурса
             */
            $resource = Yii::$app->registry->getStorageResource($storedFile->serviceId, $storedFile->resourceId);

            $xml = $dom->saveXML();
            $resource->updateData($storedFile->path, $xml);

            /**
             * @todo: разобраться, почему без перезагрузки теряется _rootnode при загрузкe контент-модели FileAct
             */
            $this->state->cyxDoc->loadXML($xml);

            $this->state->document->updateStatus(self::SUCCESSFUL_PROCESSED_STATE);

        } catch(Exception $ex) {
            $this->log($ex);
            $this->state->document->updateStatus(self::ERRONEOUS_PROCESSED_STATE);

            return false;
        }

        return true;
    }

}
