<?php
namespace addons\swiftfin\models\documents\mt\widgets;

use yii\base\Widget;
use addons\swiftfin\models\documents\mt\mtUniversal\Sequence as Model;
use yii\widgets\ActiveForm;

class Sequence extends Widget
{
    /** @var Model */
    public $model;
    /** @var ActiveForm */
    public $form;

    public function run()
    {
        echo $this->render('sequence', [
            'model' => $this->model,
            'form'  => $this->form,
        ]);
    }
}
