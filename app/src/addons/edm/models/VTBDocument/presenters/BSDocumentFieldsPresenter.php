<?php

namespace addons\edm\models\VTBDocument\presenters;

use addons\edm\models\VTBDocument\presenters\config\BSDocumentPresenterConfig;
use addons\edm\models\VTBDocument\presenters\config\BSDocumentPresenterConfigFactory;
use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\BSDocumentAttachment;
use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\CompoundField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\Field;
use common\models\vtbxml\documents\fields\MoneyField;
use yii\helpers\Html;
use yii\helpers\Url;

class BSDocumentFieldsPresenter
{
    /** @var Field[] */
    private $fields;

    /** @var BSDocumentPresenterConfig  */
    public $config;

    /**
     * BSDocumentFieldsPresenter constructor.
     * @param string $documentType
     * @param Field[] $fields
     */
    public function __construct($documentType, $fields)
    {
        $this->config = BSDocumentPresenterConfigFactory::createForType($documentType);

        $order = $this->config->getCustomFieldOrder();
        if (empty($order)) {
            $this->fields = $fields;
        } else {
            $this->fields = [];
            foreach($order as $fieldId) {
                $this->fields[$fieldId] = isset($fields[$fieldId])
                        ? $fields[$fieldId]
                        : new CompoundField([
                            'isRequired'  => false,
                            'isSigned'    => false,
                            'description' => '',
                            'length'      => 0,
                            'versions'    => [5, 6],
                        ]);
                unset($fields[$fieldId]);
            }

            // Это остальные поля, которые по идее не нужны
            // $this->fields += $fields;
            // добавим только блобы
            foreach($fields as $fieldId => $field) {
                if ($field instanceOf BlobTableField) {
                    $this->fields[$fieldId] = $field;
                }
            }
        }
    }

    public function buildDetailViewAttributes(BSDocument $document, $documentId = null)
    {
        $this->config->setDocument($document);
        $attributes = [];
        foreach ($this->fields as $fieldId => $field) {
            if ($this->isExcluded($fieldId)
                || $field instanceof BlobTableField
                || $field instanceof AttachmentField
            ) {
                continue;
            }
            $attributes[] = [
                'attribute' => $fieldId,
                'format'    => 'html',
                'label'     => $this->getLabel($fieldId),
                'value'     => $this->getHtmlValue($document, $fieldId, $documentId),
            ];
        }

        return $attributes;
    }

    public function isExcluded($fieldId)
    {
        return $this->config->isExcludedField($fieldId);
    }

    public function getLabel($fieldId)
    {
        $customLabel = $this->config->getCustomLabel($fieldId);

        return $customLabel === null
            ? $this->fields[$fieldId]->description
            : $customLabel;
    }

    public function getHtmlValue(BSDocument $document, $fieldId, $documentId = null)
    {
        $field = $this->fields[$fieldId];
        $value = $field instanceof CompoundField
                ? ''
                : $document->$fieldId;

        /** @var callable|null $valueCallback */
        $valueCallback = $this->config->getHtmlValueCallback($fieldId);
        if ($valueCallback !== null) {
            if (is_callable($valueCallback)) {
                return $valueCallback($value);
            }

            return $valueCallback;
        }

        if ($value === null || $value === '' || $value === false) {
            return '';
        }

        if ($field instanceof DateField) {
            return $value->format('d.m.Y');
        }

        if ($field instanceof DateTimeField) {
            $date = $value->format('d.m.Y H:i:s');
            if (substr($date, -9) == ' 00:00:00') {
                $date = substr($date, 0, strlen($date) - 9);
            }

            return $date;
        }

        if ($field instanceof MoneyField || ($field instanceof DoubleField && strpos($fieldId, 'AMOUNT') !== false)) {
            $value = sprintf('%.2f', $value);
        } else if ($field instanceof AttachmentField) {
            $attachments = $value;
            $downloadLinks = array_map(
                function (BSDocumentAttachment $attachment, $index) use ($fieldId, $documentId) {
                    return Html::a(
                        $attachment->fileName,
                        Url::to(['/edm/vtb-documents/download-attachment', 'id' => $documentId, 'fieldId' => $fieldId, 'index' => $index])
                    );
                },
                $attachments,
                array_keys($attachments)
            );

            return empty($downloadLinks) ? null : implode(', ', $downloadLinks);
        }

        return Html::encode($value);
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

}
