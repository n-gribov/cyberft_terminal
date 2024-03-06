<?php

namespace backend\controllers\helpers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

trait TerminalCodes
{
    protected $traitBehaviorsRules = [
        'allow' => true,
        'actions' => ['terminal-codes'],
        'roles' => ['@'],
	];

    /**
	 * Возвращает массив кодов терминалов, соответствующих получателю $id
	 * @param $id
	 * @return array
	 * @throws NotFoundHttpException
	 */
	public function actionTerminalCodes($id)
	{
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			return [
                'more' => false,
				'results' => Yii::$app->getModule('certManager')->getTerminalCodesByParticipant($id)
			];
		} else {
			throw new NotFoundHttpException(Yii::t('app', 'This request must not be called directly'));
		}
	}

}