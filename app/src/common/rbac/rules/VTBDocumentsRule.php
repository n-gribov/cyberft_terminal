<?php
namespace common\rbac\rules;

use common\helpers\vtb\VTBHelper;
use common\models\TerminalRemoteId;
use common\models\UserTerminal;
use Yii;
use yii\rbac\Rule;

class VTBDocumentsRule extends Rule
{
    public $name = 'vtbDocumentsRule';

    public function execute($userId, $item, $params)
    {
        if (empty(Yii::$app->user) || empty(Yii::$app->user->identity)) {
            return false;
        }

        $vtbTerminalId = VTBHelper::getGatewayTerminalAddress();
        if (empty($vtbTerminalId)) {
            return false;
        }

        $terminalId = Yii::$app->user->identity->terminalId;
        if (!empty($terminalId)) {
            $terminalIds = [$terminalId];
        } else {
            $terminalIds = array_keys(UserTerminal::getUserTerminalIds(Yii::$app->user->identity->id));
        }

        $vtbClientsCount = TerminalRemoteId::find()
            ->where(['terminalReceiver' => $vtbTerminalId])
            ->andWhere(['terminalId' => $terminalIds])
            ->count();

        return $vtbClientsCount > 0;
    }
}
