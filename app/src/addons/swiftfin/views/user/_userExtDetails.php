<?php

/** @var \addons\swiftfin\models\SwiftfinUserExt $userExtModel */
/** @var \common\base\BaseBlock $module */
/** @var string $serviceName */

?>

<h3><?= Yii::t('app/menu', $serviceName) ?></h3>
<?= $this->render('_userExtRolesDetails', ['userExtModel' => $userExtModel]) ?>
<?= $this->render('@common/views/user/_userExtPermissionsDetails', ['userExtModel' => $userExtModel, 'module' => $module]) ?>
