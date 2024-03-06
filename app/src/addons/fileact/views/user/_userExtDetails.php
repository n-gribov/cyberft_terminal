<h3><?= Yii::t('app/menu', $serviceName) ?></h3>
<?php
    // Вывести детали ключа Криптопро
    echo $this->render('_userExtCryptoproKeysDetails', ['userExtModel' => $userExtModel]);
    // Вывести права пользвоателя
    echo $this->render('@common/views/user/_userExtPermissionsDetails',
            ['userExtModel' => $userExtModel, 'module' => $module]);
