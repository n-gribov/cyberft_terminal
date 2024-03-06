<?php

namespace common\jobs;

use addons\ISO20022\helpers\ISO20022Helper;
use common\base\interfaces\SignableType;

/**
 * Класс задания для добавления данных подписи в документы, у которых подпись находится в теле документа
 *
 * @package common\jobs
 */
class DocumentContentSignJob extends BaseDocumentSignJob
{
    protected function injectSignature()
    {
        /** @var SignableType $typeModel */
        $typeModel = $this->_cyxDoc->getContent()->getTypeModel();

        $isInjected = $typeModel->injectSignature($this->_signature, $this->_certBody);
        if (!$isInjected) {
            $this->log('Failed to inject signature into type model');

            return false;
        }

        // Если модель использует сжатие в zip
        if (!empty($typeModel->useZipContent)) {
            ISO20022Helper::updateZipContent($typeModel);
        }

        $this->_cyxDoc->getContent()->markDirty();

        return true;
    }
}
