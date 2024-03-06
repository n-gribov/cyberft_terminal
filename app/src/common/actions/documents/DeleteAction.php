<?php

namespace common\actions\documents;

use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\ControllerCache;
use common\helpers\DocumentHelper;
use common\helpers\UserHelper;
use common\models\User;
use Yii;
use yii\base\Action;

class DeleteAction extends Action
{
    public $serviceId;

    private $deletedCount = 0;
    private $failedCount = 0;

    public function run()
    {
        $ids = Yii::$app->request->post('id');

        foreach ($ids as $id) {
            $this->deleteDocument((int) $id);
        }

        $this->cleanSelectionCache();

        $this->setResultsFlashMessages();
        $this->controller->redirect(Yii::$app->request->referrer);
    }

    private function deleteDocument($id)
    {
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);
        if ($document === null) {
            return $this->registerError("Document $id is not found or is not accessible by current user");
        }

        if (!$document->isDeletable()) {
            return $this->registerError("Document $id is not deletable");
        }
        if (!$this->userCanDeleteDocument($document)) {
            return $this->registerError("User has no permission to delete document $id");
        }

        $isUpdated = DocumentHelper::updatedocumentStatus($document, Document::STATUS_DELETED);
        if (!$isUpdated) {
            return $this->registerError("Failed to update status for document $id");
        } else {
            $this->logDeleteEvent($document);
        }
        $this->deletedCount++;
    }

    private function registerError($errorMessage)
    {
        Yii::info($errorMessage);
        $this->failedCount++;
    }

    private function setResultsFlashMessages()
    {
        if ($this->deletedCount > 0) {
            Yii::$app->session->setFlash('success', $this->getSuccessMessage());
        }
        if ($this->failedCount > 0) {
            Yii::$app->session->setFlash('error', $this->getErrorMessage());
        }
    }

    private function getSuccessMessage()
    {
        return $this->deletedCount == 1 && $this->failedCount == 0
            ? Yii::t('document', 'Document is successfully deleted')
            :Yii::t(
                'document',
                '{count} documents are successfully deleted',
                ['count' => $this->deletedCount]
            );
    }

    private function getErrorMessage()
    {
        return $this->deletedCount == 0 && $this->failedCount == 1
            ? Yii::t('document', 'Failed to delete document')
            : Yii::t(
                'document',
                '{count} documents were not deleted',
                ['count' => $this->failedCount]
            );
    }

    /**
     * @param Document $document
     * @return boolean
     */
    private function userCanDeleteDocument($document): bool
    {
        $user = Yii::$app->user;

        $userIsAdmin = in_array($user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN]);
        if ($userIsAdmin) {
            return true;
        }

        return $user->can(
            DocumentPermission::DELETE,
            [
                'serviceId' => $this->serviceId,
                'document' => $document,
            ]
        );
    }

    private function logDeleteEvent($document)
    {
        Yii::$app->monitoring->log(
            'user:deleteDocument',
            'document',
            $document->id,
            [
                'userId' => Yii::$app->user->id,
                'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
            ]
        );
    }

    private function cleanSelectionCache(): void
    {
        if ($this->deletedCount === 0) {
            return;
        }

        $entriesSelectionCacheKey = Yii::$app->request->post('entriesSelectionCacheKey');
        if (!$entriesSelectionCacheKey) {
            return;
        }

        (new ControllerCache($entriesSelectionCacheKey))->clear();
    }
}
