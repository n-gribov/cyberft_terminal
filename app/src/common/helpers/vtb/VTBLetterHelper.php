<?php

namespace common\helpers\vtb;

use addons\edm\models\VTBFreeBankDoc\VTBFreeBankDocType;
use addons\edm\models\VTBFreeClientDoc\VTBFreeClientDocType;
use common\base\BaseType;
use common\models\vtbxml\documents\BSDocumentAttachment;

class VTBLetterHelper
{
    /**
     * @param BaseType $typeModel
     * @param string $targetDirPath
     * @return array
     */
    public static function extractAttachments(BaseType $typeModel, string $targetDirPath): array
    {
        if ($typeModel instanceof VTBFreeBankDocType || $typeModel instanceof VTBFreeClientDocType) {
            $attachments = [];
            foreach ($typeModel->document->DOCATTACHMENT as $index => $attachment) {
                /** @var BSDocumentAttachment $attachment */

                $filePath = "$targetDirPath/$index";
                $saveResult = file_put_contents($filePath, $attachment->fileContent);
                if ($saveResult === false) {
                    throw new \Exception("Failed to save file to $filePath");
                }

                $attachments[$attachment->fileName] = $filePath;
            }
            return $attachments;
        }

        throw new \InvalidArgumentException("Unsupported document type: {$typeModel->getType()}");
    }
}
