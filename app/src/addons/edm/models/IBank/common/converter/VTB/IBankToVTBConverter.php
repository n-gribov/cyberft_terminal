<?php

namespace addons\edm\models\IBank\common\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\IBank\common\converter\IBankConverter;
use addons\edm\models\IBank\common\IBankDocument;
use common\base\BaseType;
use common\helpers\vtb\VTBHelper;
use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\BSDocumentAttachment;
use common\models\vtbxml\service\SignInfo;
use Yii;

abstract class IBankToVTBConverter implements IBankConverter
{
    const EXT_MODEL_CLASS = null;
    const TYPE_MODEL_CLASS = null;
    const VTB_DOCUMENT_VERSION = null;
    const MAPPING = [];

    public function createTypeModel(IBankDocument $ibankDocument, $senderTerminalId, $recipientTerminalId, $attachmentsFilesPaths = []): BaseType
    {
        $vtbDocument = $this->createVTBDocument($ibankDocument, $senderTerminalId);
        $typeModelClass = static::getTypeModelClass();

        /** @var BaseVTBDocumentType $typeModel */
        $typeModel = new $typeModelClass();
        $typeModel->document = $vtbDocument;
        $typeModel->customerId = $this->getVTBCustomerId($senderTerminalId);
        $typeModel->documentVersion = static::VTB_DOCUMENT_VERSION;
        $typeModel->signatureInfo = new SignInfo([
            'signedFields' => $vtbDocument->getSignedFieldsIds(static::VTB_DOCUMENT_VERSION)
        ]);

        $this->afterCreateTypeModel($typeModel, $ibankDocument);
        $this->addExternalAttachments($typeModel, $attachmentsFilesPaths);

        return $typeModel;
    }

    public function createExtModelAttributes(BaseType $typeModel): array
    {
        $extModelClass = $this->getExtModelClass();
        if ($extModelClass === null) {
            return [];
        }
        $extModel = new $extModelClass();
        $extModel->loadContentModel($typeModel);
        return $extModel->dirtyAttributes;
    }

    public function getExtModelClass(): string
    {
        return static::EXT_MODEL_CLASS;
    }

    abstract protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument);

    abstract protected function createBSDocument($documentClass, $mapping, $values, $topLevelBsDocument = null);

    protected function createVTBDocument(IBankDocument $ibankDocument, $senderTerminalId)
    {
        $vtbDocumentClass = static::getVTBDocumentClass();
        $document = $this->createBSDocument(
            $vtbDocumentClass,
            static::MAPPING,
            $ibankDocument->getData()
        );

        if (property_exists($vtbDocumentClass, 'CUSTID')) {
            $document->CUSTID = $this->getVTBCustomerId($senderTerminalId);
        }
        if (property_exists($vtbDocumentClass, 'KBOPID')) {
            $document->KBOPID = $this->getVTBBranchId($ibankDocument);
        }

        return $document;
    }

    protected static function getTypeModelClass()
    {
        return static::TYPE_MODEL_CLASS;
    }

    protected static function getVTBDocumentClass()
    {
        $typeModelClass = static::getTypeModelClass();

        return $typeModelClass::VTB_DOCUMENT_CLASS;
    }

    protected static function parseDate($value)
    {
        if (preg_match('/^\d+\.\d+\.\d+$/', $value)) {
            $value .= ' 00:00:00';
        }
        $format = preg_match('/^\d+\.\d+\.\d{2} /', $value) ? 'd.m.y H:i:s' : 'd.m.Y H:i:s';

        $date = \DateTime::createFromFormat($format, $value);
        return static::isValidDate($date) ? $date : false;
    }

    protected static function isValidDate($date)
    {
        if (!$date) {
            return false;
        }

        return \DateTime::createFromFormat('d.m.Y H:i:s', $date->format('d.m.Y H:i:s')) !== false;
    }

    protected function getVTBCustomerId($senderTerminalId)
    {
        $vtbCustomerId = VTBHelper::getVTBCustomerId($senderTerminalId);
        if (empty($vtbCustomerId)) {
            throw new \Exception("VTB customer id for terminal $senderTerminalId is not found");
        }

        return $vtbCustomerId;
    }

    protected function getVTBBranchId(IBankDocument $ibankDocument)
    {
        $accountNumber = $ibankDocument->getSenderAccountNumber();
        if (!empty($accountNumber)) {
            return $this->getVTBBranchIdByAccountNumber($accountNumber);
        }

        // Временное решение: берем id первого филиала
        $vtbBranches = DictVTBBankBranch::find()->orderBy(['branchId' => SORT_ASC])->limit(1)->all();

        return empty($vtbBranches) ? null : $vtbBranches[0]->branchId;
    }

    protected function getVTBBranchIdByAccountNumber($accountNumber)
    {
        $payerAccount = EdmPayerAccount::findOne(['number' => $accountNumber]);
        if ($payerAccount === null) {
            return null;
        }

        $vtbBranch = DictVTBBankBranch::findOne(['bik' => $payerAccount->bankBik]);

        return $vtbBranch === null ? null : $vtbBranch->branchId;
    }

    protected function addExternalAttachments($typeModel, $attachmentsFilesPaths)
    {
        if (count($attachmentsFilesPaths) === 0) {
            return;
        }

        /** @var BSDocument $document */
        $document = $typeModel->document;
        if (!property_exists($document, 'DOCATTACHMENT')) {
            return;
        }

        $iconsPath = Yii::getAlias('@common/models/vtbxml/documents/resources/attachment');
        $icon16Content = file_get_contents("$iconsPath/icon16.ico");
        $icon32Content = file_get_contents("$iconsPath/icon32.ico");

        foreach ($attachmentsFilesPaths as $filePath) {
            $fileContent = file_get_contents($filePath);
            $document->DOCATTACHMENT[] = new BSDocumentAttachment([
                'fileName'      => static::extractFileName($filePath),
                'fileContent'   => $fileContent,
                'icon16Content' => $icon16Content,
                'icon32Content' => $icon32Content,
            ]);
        }
    }

    protected static function extractFileName($filePath)
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, $filePath);
        return end($pathParts);
    }
}