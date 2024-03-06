<h3><?= Yii::t('app/menu', $serviceName) ?></h3>
<?php
// Вывести детализацию роли пользователя
echo $this->render('_userExtRolesDetails', ['userExtModel' => $userExtModel]);
// Вывести детализацию прав пользователя
echo $this->render('@common/views/user/_userExtPermissionsDetails',
        ['userExtModel' => $userExtModel, 'module' => $module]);

