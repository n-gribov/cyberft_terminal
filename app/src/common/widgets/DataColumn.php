<?php
namespace common\widgets;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQueryInterface;
use yii\grid\DataColumn as YiiDataColumn;
use yii\helpers\Inflector;

class DataColumn extends YiiDataColumn
{
    protected $textAlign;
    protected $width;
    public $nowrap = false;
    public $sortLinkOptions = ['class' => 'sortable'];

    // Регулирует функционал преобразования и отображения заголовка ячейки таблицы
    public $optimizeCellLabel = true;

    public function init() {
        if (is_array($this->filterInputOptions)) {
            $this->filterInputOptions['class'] = 'form-control';
        }

        if (is_array($this->contentOptions)) {
            if ($this->nowrap === true) {
                $this->contentOptions = array_merge(['nowrap' => 'true'], $this->contentOptions);
            }
            $this->contentOptions['data']['attribute'] = $this->attribute;
        }

        parent::init();
    }

    protected function renderHeaderCellContent()
    {
        if ($this->header !== null || $this->label === null && $this->attribute === null) {
            return parent::renderHeaderCellContent();
        }

        $provider = $this->grid->dataProvider;

        if ($this->label === null) {
            $model = null;
            if ($provider instanceof ActiveDataProvider && $provider->query instanceof ActiveQueryInterface) {
                $model = new $provider->query->modelClass();
            } else {
                if ($provider instanceof ArrayDataProvider && $provider->modelClass) {
                    $model = new $provider->modelClass();
                } else {
                    $models = $provider->getModels();
                    $model = reset($models);
                }
            }
            if ($model instanceof Model) {
                $label = $model->getAttributeLabel($this->attribute);
            } else {
                $label = Inflector::camel2words($this->attribute);
            }
        } else {
            $label = $this->label;
        }

        $labelWords = explode(' ', preg_replace('/\s+/', ' ', trim($label)));
        $labelShortcut = '';
        if (count($labelWords) > 1) {
            foreach ($labelWords as $word) {
                $labelShortcut.= mb_strtoupper(mb_substr($word, 0, 1)) . '.';
            }
        }

        if (!empty($labelShortcut) && $this->optimizeCellLabel) {
            $label = '<span class="hidden-xlg" data-toggle="tooltip" data-placement="bottom" title="' . $label .  '" data-original-title="'. $label . '">' . $labelShortcut . '</span>
                    <span class="visible-xlg">'. $label . '</span>';
        }

        if ($this->attribute !== null && $this->enableSorting &&
            ($sort = $provider->getSort()) !== false && $sort->hasAttribute($this->attribute)) {

            return $sort->link($this->attribute, array_merge($this->sortLinkOptions, ['label' => $label]));
        } else {
            return $label;
        }
    }

    public function setWidth($value)
    {
        switch ($value) {
            case 'narrow':
                $this->filterInputOptions['maxLength'] = 10;
                $this->filterInputOptions['style'] = 'width: 50px';

                break;
            case 'medium':
                $this->filterInputOptions['style'] = 'width: 100px';

                break;
            case 'wide':
                $this->filterInputOptions['style'] = 'width: 300px';

                break;
        }

    }

    public function setTextAlign($value)
    {
        if ($value == 'right') {
                $this->headerOptions['class'] =
                $this->filterOptions['class'] =
                $this->contentOptions['class'] = 'text-right';
        } else if ($value == 'center') {
                $this->headerOptions['class'] =
                $this->filterOptions['class'] =
                $this->contentOptions['class'] = 'text-center';
        }
    }

}