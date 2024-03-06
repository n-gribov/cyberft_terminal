<?php

/** @var \addons\fileact\models\FileActUserExt $userExtModel */
/** @var \common\base\BaseBlock $module */
/** @var string $serviceName */

?>

<h3><?= Yii::t('app/menu', $serviceName) ?></h3>
<?= $this->render('_userExtCryptoproKeysDetails', ['userExtModel' => $userExtModel]) ?>
<?= $this->render('@common/views/user/_userExtPermissionsDetails', ['userExtModel' => $userExtModel, 'module' => $module]) ?>
