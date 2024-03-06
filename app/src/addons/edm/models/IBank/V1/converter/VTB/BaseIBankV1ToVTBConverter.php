<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\DictBank;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\DictVTBGroundDocumentType;
use addons\edm\models\IBank\common\converter\VTB\IBankToVTBConverter;
use addons\edm\models\IBank\common\IBankDocument;
use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\BSDocumentAttachment;
use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\StringField;
use Yii;

abstract class BaseIBankV1ToVTBConverter extends IBankToVTBConverter
{
    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        $this->fixGroundDocumentsType($typeModel);
    }

    protected function fixGroundDocumentsType(BaseVTBDocumentType $typeModel)
    {
        /** @var BSDocument $document */
        $document = $typeModel->document;

        $groundDocumentProperty = null;
        foreach (['GROUNDDOCUMENTS', 'GROUNDRECEIPTSBLOB'] as $property) {
            if (property_exists($document, $property)) {
                $groundDocumentProperty = $property;
                break;
            }
        }

        if ($groundDocumentProperty === null || empty($document->$groundDocumentProperty)) {
            return;
        }

        foreach ($document->$groundDocumentProperty as $groundDocument) {
            if (!property_exists($groundDocument, 'DOCTYPECODE') || empty($groundDocument->DOCTYPECODE)) {
                continue;
            }
            $type = DictVTBGroundDocumentType::findOneByCode($groundDocument->DOCTYPECODE);
            if ($type === null) {
                continue;
            }

            $groundDocument->DOCUMENTTYPE = $type->name;

            // В случае, когда в качестве типа представленного обосновывающего документа в поле DOCUMENTTYPE указан тип,
            // соответствующий «Уникальный номер контракта (кредитного договора)», указывается значение «UNC»,
            // и не заполняется в остальных случаях.
            if ($groundDocument->DOCTYPECODE !== 'UNC') {
                $groundDocument->DOCTYPECODE = null;
            }
        }
    }

    protected function createBSDocument($documentClass, $mapping, $values, $topLevelBsDocument = null)
    {
        /** @var BSDocument $bsDocument */
        $bsDocument = new $documentClass();

        if ($topLevelBsDocument === null) {
            $topLevelBsDocument = $bsDocument;
        }

        $fields = $bsDocument->getFields();

        foreach ($mapping as $i => $mappedField) {
            if ($mappedField === null) {
                continue;
            }

            if (is_array($mappedField)) {
                $fieldId = array_keys($mappedField)[0];
                $nestedAttributes = array_values($mappedField)[0];
                $nestedValues = $values[$i];

                $field = $fields[$fieldId];

                if ($field instanceof BlobTableField) {
                    foreach ($nestedValues as $nestedValue) {
                        $nestedDocumentClass = "common\models\\vtbxml\documents\\{$field->recordType}";
                        $bsDocument->$fieldId = array_merge(
                            $bsDocument->$fieldId,
                            [$this->createBSDocument($nestedDocumentClass, $nestedAttributes, $nestedValue, $topLevelBsDocument)]
                        );
                    }
                } else if ($field instanceof AttachmentField) {
                    $iconsPath = Yii::getAlias('@common/models/vtbxml/documents/resources/attachment');
                    $icon16Content = file_get_contents("$iconsPath/icon16.ico");
                    $icon32Content = file_get_contents("$iconsPath/icon32.ico");

                    foreach ($nestedValues as $nestedValue) {
                        $fileNameKey = array_search('FILENAME', $nestedAttributes);
                        $fileContentKey = array_search('FILECONTENT', $nestedAttributes);
                        $fileIcon16Key = array_search('ICON16', $nestedAttributes);
                        $fileIcon32Key = array_search('ICON32', $nestedAttributes);

                        $fileName = $nestedValue[$fileNameKey];
                        $fileContent = base64_decode($nestedValue[$fileContentKey]);

                        if ($fileIcon16Key !== false) {
                            $icon16Content = $nestedValue[$fileIcon16Key];
                        }

                        if ($fileIcon32Key !== false) {
                            $icon32Content = $nestedValue[$fileIcon32Key];
                        }

                        $attachment = new BSDocumentAttachment([
                            'fileName' => $fileName,
                            'fileContent' => $fileContent,
                            'icon16Content' => $icon16Content,
                            'icon32Content' => $icon32Content,
                        ]);

                        $bsDocument->$fieldId = array_merge(
                            $bsDocument->$fieldId,
                            [$attachment]
                        );
                    }
                } else {
                    throw new \Exception("Field for $fieldId must be an BlobTableField or AttachmentField instance");
                }
            } else {
                $fieldId = $mappedField;
                $field = $fields[$fieldId];
                $value = $values[$i];

                if (!empty($value) && ($field instanceof DateField || $field instanceof DateTimeField)) {
                    $value = static::parseDate($value);
                    if ($value === false) {
                        $documentNumber = property_exists($topLevelBsDocument, 'DOCUMENTNUMBER') ? $topLevelBsDocument->DOCUMENTNUMBER : null;
                        $errorMmessage = Yii::t('edm', 'Invalid date format in document №{number}', ['number' => $documentNumber]);
                        throw new \DomainException($errorMmessage);
                    }
                }

                $shouldAppendValue = $field instanceof StringField
                    && $bsDocument->$fieldId !== null && $bsDocument->$fieldId !== '';
                if ($shouldAppendValue) {
                    $bsDocument->$fieldId = implode(
                        ' ',
                        array_filter(
                            [$bsDocument->$fieldId, $value],
                            function ($value) {
                                return $value !== '' && $value !== null;
                            }
                        )
                    );
                } else {
                    $bsDocument->$fieldId = $value;
                }
            }
        }

        return $bsDocument;
    }

    protected function fillCustomerBankAttributes(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        /** @var BSDocument $document */
        $document = $typeModel->document;
        $setDocumentAttribute = function (BSDocument $document, string $property, $value) {
            if (property_exists($document, $property)) {
                $document->$property = $value;
            }
        };

        $bankBranch = null;
        if (property_exists($document, 'KBOPID') && !empty($document->KBOPID)) {
            $bankBranch = DictVTBBankBranch::findOne(['branchId' => $document->KBOPID]);
            if ($bankBranch === null && !empty($ibankDocument->getSenderBik())) {
                $bankBranch = DictVTBBankBranch::findOne(['bik' => $ibankDocument->getSenderBik()]);
            }
        }

        if ($bankBranch !== null) {
            $setDocumentAttribute($document, 'CUSTOMERBANKNAME', $bankBranch->name);
            $setDocumentAttribute($document, 'CUSTOMERBANKBIC', $bankBranch->bik);
            $setDocumentAttribute($document, 'BANKVKFULLNAME', $bankBranch->fullName);

            return;
        }

        if (empty($ibankDocument->getSenderBik())) {
            return;
        }

        $edmBank = DictBank::findOne(['bik' => $ibankDocument->getSenderBik()]);
        if ($edmBank !== null) {
            $setDocumentAttribute($document, 'CUSTOMERBANKNAME', $edmBank->name);
            $setDocumentAttribute($document, 'CUSTOMERBANKBIC', $edmBank->bik);
            $setDocumentAttribute($document, 'BANKVKFULLNAME', $edmBank->name);
        }
    }
}
