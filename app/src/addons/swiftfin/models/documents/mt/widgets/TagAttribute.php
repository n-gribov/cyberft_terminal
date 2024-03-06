<?php
namespace addons\swiftfin\models\documents\mt\widgets;

use yii\base\Widget;
use addons\swiftfin\models\documents\mt\mtUniversal\Tag as Model;
use yii\widgets\ActiveForm;

class TagAttribute extends Widget
{
    /** @var Model */
    public $model;
    /** @var ActiveForm */
    public $form;
    /** @var string */
    public $attribute;
    /** @var bool */
    public $disableContainer = true;

    public function run()
    {
        echo $this->render('tagAttribute', [
            'model'            => $this->model,
            'attribute'        => $this->attribute,
            'form'             => $this->form,
            'disableContainer' => $this->disableContainer
        ]);
    }
}
