<?php

namespace addons\edm\models\VTBDocument\presenters;

use addons\edm\models\VTBDocument\presenters\config\BSDocumentPresenterConfig;
use addons\edm\models\VTBDocument\presenters\config\BSDocumentPresenterConfigFactory;

class BSDocumentTablePresenter
{
    /** @var BSDocumentFieldsPresenter */
    private $fieldsPresenter;

    /** @var BSDocumentPresenterConfig  */
    private $config;

    public function __construct($documentType, $fields)
    {
        $this->fieldsPresenter = new BSDocumentFieldsPresenter($documentType, $fields);
        $this->config = BSDocumentPresenterConfigFactory::createForType($documentType);
    }

    public function buildGridViewColumns()
    {
        $fieldsPresenter = $this->fieldsPresenter;
        $tableViewFieldsIds = $this->config->getTableViewFieldsIds();

        if (empty($tableViewFieldsIds)) {
            $tableViewFieldsIds = array_keys($fieldsPresenter->getFields());
        }

        $columns = [];
        foreach ($tableViewFieldsIds as $fieldId) {
            $columns[$fieldId] = [
                'label' => $this->fieldsPresenter->getLabel($fieldId),
                'value' => function ($model, $key, $index, $column) use ($fieldsPresenter, $fieldId) {
                    $fieldsPresenter->config->setDocument($model);

                    return $fieldsPresenter->getHtmlValue($model, $fieldId);
                },
                'format' => 'raw',
            ];
        }

        return $columns;
    }

}
