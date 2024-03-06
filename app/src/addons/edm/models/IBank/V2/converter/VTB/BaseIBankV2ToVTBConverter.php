<?php

namespace addons\edm\models\IBank\V2\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\IBank\common\converter\VTB\IBankToVTBConverter;
use addons\edm\models\IBank\common\IBankDocument;
use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\DateTimeField;
use Yii;

abstract class BaseIBankV2ToVTBConverter extends IBankToVTBConverter
{
    public function getExtModelClass(): string
    {
        return static::EXT_MODEL_CLASS;
    }

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
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
            if ($mappedField === null || !isset($values[$i])) {
                continue;
            }

            if (is_array($mappedField)) {
                $nestedValues = $values[$i];

                foreach ($nestedValues as $nestedValuesItem) {
                    $data = [];

                    foreach ($nestedValuesItem as $id => $element) {
                        if (isset($mappedField[$id])) {
                            $mappedFieldValue = $mappedField[$id];

                            $key = array_keys($mappedFieldValue)[0];
                            $keyValue = $mappedFieldValue[$key];

                            if (isset($data[$key][$keyValue])) {
                                $data[$key][$keyValue] .= $element;
                            } else {
                                $data[$key][$keyValue] = $element;
                            }
                        }
                    }

                    foreach ($data as $type => $item) {
                        $field = $fields[$type];

                        $class = "common\models\\vtbxml\documents\\{$field->recordType}";

                        $nestedMapping = array_combine(array_keys($item), array_keys($item));
                        $bsDocument->$type = array_merge($bsDocument->$type, [$this->createBSDocument($class, $nestedMapping, $item, $topLevelBsDocument ?: $bsDocument)]);
                    }

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

                $bsDocument->$fieldId = $value;
            }

        }

        return $bsDocument;
    }
}
