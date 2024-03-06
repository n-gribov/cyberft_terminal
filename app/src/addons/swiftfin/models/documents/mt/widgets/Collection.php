<?php
namespace addons\swiftfin\models\documents\mt\widgets;

use yii\base\Widget;
use addons\swiftfin\models\documents\mt\mtUniversal\Sequence as Model;
use yii\widgets\ActiveForm;

class Collection extends Widget
{
	/**
	 * @var Model
	 */
	public $model;
	/**
	 * @var ActiveForm
	 */
	public $form;

	public function run()
    {
		print $this->render('collection', [
			'model' => $this->model,
			'form'  => $this->form,
		]);
	}

}