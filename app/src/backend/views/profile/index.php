<?php
use common\helpers\UserHelper;

$this->title = Yii::t('app/profile', 'User profile');

echo $this->render('@backend/views/user/view', [
    'model' => $model,
    'dataProvider' => $dataProvider,
    'accountOperationDataProvider' => $accountOperationDataProvider,
    'filterModel'  => $filterModel,
    'activeTab' => 'info',
    'certDataProvider' => $certDataProvider,
    'accounts' => UserHelper::getViewableUserAccounts($model),
    'addonsServiceAccess' => UserHelper::getUserAddonsServiceAccess($model),
    'additionalServiceAccess' => UserHelper::getUserAdditionalServiceAccess($model),
    'servicesSettingsForm' => $servicesSettingsForm,
    'uploadCertForm' => $uploadCertForm,
]);
?>