<?php

namespace addons\edm\states\out;

use addons\ISO20022\models\ISO20022Type;
use common\exceptions\InvalidImportedDocumentException;
use common\helpers\FileHelper;
use common\helpers\StringHelper;
use common\models\ImportError;
use common\states\BaseDocumentStep;
use Yii;

abstract class ISO20022DocumentPrepareStep extends BaseDocumentStep
{
    protected function validateXml(ISO20022Type $typeModel): void
    {
        if (!$this->shouldValidateXml()) {
            return;
        }
        $typeModel->validateXSD();
        if ($typeModel->hasErrors('xml')) {
            throw new \DomainException(
                Yii::t('other', 'Source document validation against XSD failed') . ': ' . $typeModel->getFirstError('xml')
            );
        }
    }

    private function shouldValidateXml(): bool
    {
        return (bool)Yii::$app->settings->get('app')->validateXmlOnImport;
    }

    protected function handleException(\Exception $exception): void
    {
        $this->log($exception);

        $errorMessage = $exception instanceof \DomainException
            ? $exception->getMessage()
            : \Yii::t('app', 'Create document error');

        $documentNumber = $exception instanceof InvalidImportedDocumentException
            ? $exception->getDocumentNumber()
            : null;

        $this->saveImportError($errorMessage, $documentNumber);
    }

    protected function saveImportError($errorMessage, $documentNumber = null): void
    {
        $importErrorMessage = Yii::t(
            'edm',
            'Failed to create document: {error}',
            ['error' => StringHelper::mb_lcfirst($errorMessage)]
        );

        /** @var ISO20022Type $typeModel */
        $typeModel = $this->state->model;

        ImportError::createError([
            'type'                  => ImportError::TYPE_EDM,
            'filename'              => FileHelper::mb_basename($this->state->filePath),
            'identity'              => $typeModel->msgId,
            'errorDescriptionData'  => ['text' => $importErrorMessage],
            'documentType'          => $typeModel->getType(),
            'documentTypeGroup'     => $this->getDocumentTypeGroupId(),
            'documentCurrency'      => $this->getDocumentCurrency(),
            'documentNumber'        => $documentNumber,
            'senderTerminalAddress' => $this->state->sender,
        ]);
    }

    protected function getDocumentTypeGroupId(): ?string
    {
        return null;
    }

    protected function getDocumentCurrency(): ?string
    {
        return null;
    }
}
