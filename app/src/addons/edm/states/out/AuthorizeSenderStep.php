<?php

namespace addons\edm\states\out;

use common\helpers\FileHelper;
use common\helpers\StringHelper;
use common\models\ImportError;
use common\states\BaseDocumentStep;
use Yii;

/** @property-read EdmOutState $state */
class AuthorizeSenderStep extends BaseDocumentStep
{
    public $name = 'authorizeSender';

    public function run()
    {
        if (!$this->state->authorizedSender) {
            return true;
        }

        if ($this->state->sender !== $this->state->authorizedSender) {
            $errorMessage = Yii::t(
                'edm',
                'Sender authorization failure, terminal {authorizedSender} has tried to send document on behalf of {sender}',
                [
                    'authorizedSender' => $this->state->authorizedSender,
                    'sender'           => $this->state->sender,
                ]
            );
            $this->saveImportError($errorMessage);
            $this->state->addApiImportError($errorMessage);
            return false;
        }

        return true;
    }

    protected function saveImportError($errorMessage): void
    {
        $importErrorMessage = Yii::t(
            'edm',
            'Failed to create document: {error}',
            ['error' => StringHelper::mb_lcfirst($errorMessage)]
        );

        ImportError::createError([
            'type'                  => ImportError::TYPE_EDM,
            'filename'              => FileHelper::mb_basename($this->state->filePath),
            'errorDescriptionData'  => ['text' => $importErrorMessage],
            'identity'              => $this->state->apiUuid,
            'senderTerminalAddress' => $this->state->sender,
        ]);
    }
}
