<?php
namespace common\base;

use Yii;
use common\base\Controller as BaseController;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

class BaseServiceController extends BaseController
{
    public function beforeAction($action)
    {
        if (isset($this->module->serviceId)) {
            if (!Yii::$app->user->can('accessService', ['serviceId' => $this->module->serviceId])) {
                $this->redirect(['/site/redirect-if-user-inactive']);
                return false;
                //throw new ForbiddenHttpException(Yii::t('app/error', 'Service access denied for this user'));
            }
        }

        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        $isPageAction = !Yii::$app->request->isAjax
            && ($this->layout == null || preg_match('/(^|\/)main(\.php)?$/', $this->layout));
        if ($isPageAction) {
            Yii::$app->session->set('previousPageUrl', Url::current());
        }

        return parent::afterAction($action, $result);
    }

    /**
     * @param $prefix
     * @return array
     * Функция для получения параметров фильтров журналов документов
     * Требуется для организации работы кнопки Назад
     * для страниц просмотра документов
     */
    public function getSearchUrl($prefix)
    {
        $urlParams = [$prefix => []];
        $queryParams = Yii::$app->request->queryParams;
        if (isset($queryParams[$prefix])) {
            foreach($queryParams[$prefix] as $param => $value) {
                if (!empty($value)) {
                    $urlParams[$prefix][$param] = $value;
                }
            }
        }

        return $urlParams;
    }

    public function getPreviousPageUrl()
    {
        return Yii::$app->session->get('previousPageUrl');
    }

    protected function authorizePermission(string $permissionCode, array $permissionParams = [])
    {
        $isAuthorized = Yii::$app->user->can($permissionCode, $permissionParams);
        if (!$isAuthorized) {
            Yii::info("User must have $permissionCode permission, params: " . var_export($permissionParams, true));
            throw new ForbiddenHttpException();
        }
    }
}
