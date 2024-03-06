<?php

use common\helpers\UserHelper;
use yii\helpers\Html;

/** @var \common\models\BaseUserExt $userExtModel */
/** @var \common\base\BaseBlock $module */
/** @var string $serviceName */

?>

<div class="panel panel-gray">
    <div class="panel-heading">
        <h4><?= Yii::t('app', 'Extended permissions') ?></h4>
    </div>
    <div class="panel-body">
        <?php
        $permissions = UserHelper::getDocumentsPermissionsInfo($userExtModel, $module);
        if (empty($permissions)) {
            echo Yii::t('app', 'None');
        } else {
            echo "<ul>";
            foreach ($permissions as $permission) {
                echo "<li>" . Html::encode($permission) ."</li>";
            }
            echo "</ul>";
        }
        ?>
    </div>
</div>
