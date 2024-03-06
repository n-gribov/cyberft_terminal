<?php

namespace common\base;

use common\base\Controller as BaseController;
use common\helpers\UserHelper;
use common\models\BaseUserExt;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 *
 * @property BaseBlock $module
 */
class BaseUserExtController extends BaseController
{
    public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['commonUsers'],
					],
				],
			]
		];
	}

    /**
     * Get user extended data
     *
     * @param integer $id User ID
     * @return mixed
     */
	public function actionIndex($id)
	{
		$model = $this->getUserExtModel($id);
		return $this->render(
		    '@common/views/user-ext/index',
            [
                'extModel'    => $model,
                'serviceName' => $this->getServiceName()
            ]
        );
	}

    protected function getServiceName()
    {
        return $this->module->config['menu']['label'];
    }

	public function actionUpdatePermissions()
	{
		$id = (int) Yii::$app->request->get('id');
		$extModel = $this->getUserExtModel($id);

        if (empty($extModel)) {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Unknown user - data was not updated'));
            return $this->redirect(['/user']);
        }

        if (UserHelper::canUpdateProfile($extModel->userId)) {
            $permissions = \Yii::$app->request->post('permissions');
            if (empty($permissions)) {
                $permissions = [];
            }
            $extModel->permissions = array_values($permissions);

            if (!$extModel->save()) {
                Yii::$app->session->addFlash('error', Yii::t('app/user', 'Could not save user permissions'));
            }
        } else {
            Yii::$app->session->addFlash('error', Yii::t('app/user', 'Editing user is not allowed'));
        }

        return $this->redirect(['index', 'id' => $extModel->userId, 'tabMode' => 'tabPermissions']);
    }

    /**
     * Get user extended model
     *
     * @param integer $userId User Id
     * @return BaseUserExt
     * @throws NotFoundHttpException
     */
    protected function getUserExtModel($userId)
    {
        $extModel = $this->module->getUserExtmodel($userId);
		if ($extModel->getIsNewRecord()) {
            $user = User::findOne($userId);
            if (empty($user)) {
                throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
            }

            $extModel->userId = $userId;
            $extModel->canAccess = TRUE;
			$extModel->save();
		}

        return $extModel;
    }
}
