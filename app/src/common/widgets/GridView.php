<?php
namespace common\widgets;

use Closure;
use common\document\Document;
use Yii;
use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    public $tableOptions = ['class' => 'table table-dblclick table-striped table-hover'];

    public $dataColumnClass = '\common\widgets\DataColumn';

    protected $rowConfig;

    protected $highlightsByStatus;

    /**
     * Do not show summary by default
     */
    public $showSummary = false;

    //protected $doubleClickView = false;

    public $actions;

    public $colorStyleMap = ['red' => 'danger', 'yellow' => 'warning', 'active' => 'active'];

    protected function initColumns()
    {
        parent::initColumns();
        $this->addActionColumn();
    }

    public function setRowConfig($value)
    {
        $this->rowConfig = $value;
    }

    public function setHighlightsByStatus($value)
    {
        $this->highlightsByStatus = $value;
    }

    public function init()
    {
        if (true === $this->actions) {
            $this->actions = '{view} {update} {delete}';
        }

        if (!$this->showSummary) {
            $this->summary = '';
        }

        parent::init();
    }

    protected function addActionColumn()
    {
        if (!empty ($this->actions)) {
            $config = [
                'class' => \common\widgets\ActionColumn::className(),
                'grid' => $this,
            ];
            if (is_array($this->actions)) {
                $config = array_merge($config, $this->actions);
            } else {
                $config = array_merge($config, [
                    'template' => $this->actions
                ]);
            }

            $this->columns[] = Yii::createObject($config);
        }
    }

    protected function rowColor($model, $rowConfig)
    {
        $attr = $rowConfig['attrColor'];
        $attrValue = $model->$attr;
        if (isset($rowConfig['map'][$attrValue])) {
            if (isset($this->colorStyleMap[$rowConfig['map'][$attrValue]])) {
                return $this->colorStyleMap[$rowConfig['map'][$attrValue]];
            }
        }

        return '';
    }

    /**
     * Renders a table row with the given data model and key.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderTableRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {
            $cells[] = $column->renderDataCell($model, $key, $index);
        }

        if ($this->rowOptions instanceof Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }

        $rowClass = $options['class'] ?? '';

        if (isset($this->rowConfig)) {
            $rowClass .= ' ' . $this->rowColor($model, $this->rowConfig);
        }

        if (isset($this->highlightsByStatus)) {
            $rowClass .= ' ' . $this->checkHighlightingByStatus($model);
        }

        $options['class'] = $rowClass;
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

        return Html::tag('tr', implode('', $cells), $options);
    }

    /**
     * Проверка необходимости выделения строки в зависимости от статуса документа
     * @param $model
     * @return string
     */
    private function checkHighlightingByStatus($model)
    {
        if (!isset($model->status)) {
            return null;
        }

        // Ошибочные статусы
        $dangerStatuses = [
            Document::STATUS_REJECTED, Document::STATUS_SENDING_FAIL, Document::STATUS_PROCESSING_ERROR,
            Document::STATUS_SIGNING_ERROR, Document::STATUS_SIGNING_REJECTED, Document::STATUS_CONTROLLER_VERIFICATION_FAIL,
            Document::STATUS_VERIFICATION_FAILED, Document::STATUS_REGISTERING_ERROR, Document::STATUS_NOT_SENT,
            Document::STATUS_USER_VERIFICATION_ERROR, Document::STATUS_AUTOSIGNING_ERROR
        ];

        // Статусы-предупреждения
        $warningStatuses = [
            Document::STATUS_UNDELIVERED
        ];

        if (in_array($model->status, $dangerStatuses)) {
            return 'danger';
        } else if (in_array($model->status, $warningStatuses)) {
            return 'undelivered-documents';
        } else {
            return null;
        }
    }
}