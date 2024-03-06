<?php

namespace addons\ISO20022\models\traits;

use common\components\storage\AbstractFileCollection;
use common\components\xmlsec\xmlseclibs\XMLSecurityDSig;
use Exception;
use Yii;

trait WithAttachments
{
    public $useZipContent = false;
    public $zipContent;
    public $zipFilename;

    public $originalErrorMessages = [];
    public $translatedErrorMessages = [];

    public function getAttachmentInfo()
    {
        $attachment = [];

        $attachmentNodes = $this->getAttachmentNodes();

        foreach($attachmentNodes as $attch) {
            $filename = (string) $attch->URL;

            if (!$filename) {
                throw new Exception('Failed to get attachment filename');
            }

            $namespaces = $attch->getNamespaces(true);

            $digestValue = (string) $attch->LkFileHash->children($namespaces['ds'])->Reference->DigestValue;

            if (!$digestValue) {
                throw new Exception('Failed to get file digest value: ' . $filename);
            }

            $digestMethod = (string) $attch->LkFileHash->children($namespaces['ds'])->Reference->DigestMethod->attributes()->Algorithm;

            if (!$digestMethod) {
                throw new Exception('Failed to get file digest method: ' . $filename);
            }

            $attachment[$filename] = [
                'filename' => $filename,
                'digestMethod' => $digestMethod,
                'digestValue' => $digestValue
            ];
        }

        return $attachment;
    }

    public function validateAttachment(AbstractFileCollection $fileCollection)
    {
        $this->originalErrorMessages = [];
        $this->translatedErrorMessages = [];

        $attachmentInfo = $this->getAttachmentInfo();
        if (count($attachmentInfo) != $fileCollection->count()) {
            $message = 'Attachment information does not match number of files';

            $this->originalErrorMessages[] = $message;
            $this->translatedErrorMessages[] = Yii::t('other', $message);

            $this->addError('', $message);

            return false;
        }

        while ($fileCollection->next() !== false) {
            $filename = $fileCollection->getFilename();
            if (!isset($attachmentInfo[$filename])) {
                $message = 'Failed to find attachment info:';
                $originalMessage = "{$message} {$filename}";

                $this->originalErrorMessages[] = $originalMessage;
                $this->translatedErrorMessages[] = Yii::t('other', "{$message} {filename}", ['filename' => $filename]);

                $this->addError('', $originalMessage);

                continue;
            }

            $attachmentFileInfo = $attachmentInfo[$filename];

            $fileDigestValue = XMLSecurityDSig::calculateDigest(
                $attachmentFileInfo['digestMethod'],
                $fileCollection->getFilecontent()
            );

            if ($fileDigestValue != $attachmentFileInfo['digestValue']) {
                $message = 'File digest value is invalid:';
                $originalMessage = "{$message} {$filename}";

                $this->originalErrorMessages[] = $originalMessage;
                $this->translatedErrorMessages[] = Yii::t('other', "{$message} {filename}", ['filename' => $filename]);

                $this->addError('', $originalMessage);

                continue;
            }
        }

        return true;
    }

}