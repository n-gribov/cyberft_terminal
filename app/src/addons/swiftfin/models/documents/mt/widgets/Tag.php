<?php
namespace addons\swiftfin\models\documents\mt\widgets;

use yii\base\Widget;
use addons\swiftfin\models\documents\mt\mtUniversal\Tag as Model;
use yii\widgets\ActiveForm;

class Tag extends Widget
{
    /** @var Model */
    public $model;
    /** @var ActiveForm */
    public $form;

    public function run()
    {
        if ($this->model->getIsMultiTag()) {
            $view = 'tagMulti';
        } else {
            $view = 'tag';
        }

        echo $this->render($view, [
            'model' => $this->model,
            'form'  => $this->form,
        ]);
    }
}
