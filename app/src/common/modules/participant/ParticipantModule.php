<?php
namespace common\modules\participant;

use common\modules\participant\jobs\LoadDirectoryJob;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;
use yii\console\Application;

class ParticipantModule extends Module implements BootstrapInterface
{
    private const SERVICE_ID = 'participant';

    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            $this->controllerNamespace          = 'common\modules\participant\console';
            Yii::$app->controllerMap[$this->id] = [
                'class'  => 'common\modules\\' . $this->id . '\console\DefaultController',
                'module' => $this,
            ];
        }

        Yii::$app->registry->registerType('BICDirRequest', [
            'module'       => static::SERVICE_ID,
            'contentClass' => 'common\modules\participant\models\BICDirRequestCyberXmlContent',
            'typeModelClass' => 'common\modules\participant\models\BICDirRequestType',
        ]);

        Yii::$app->registry->registerType('BICDir', [
            'module'       => static::SERVICE_ID,
            'contentClass' => 'common\modules\participant\models\BICDirCyberXmlContent',
            'typeModelClass' => 'common\modules\participant\models\BICDirType',
        ]);

        Yii::$app->registry->registerRegularJob(LoadDirectoryJob::class, 3600, ['useDelay' => true]);
    }
}
