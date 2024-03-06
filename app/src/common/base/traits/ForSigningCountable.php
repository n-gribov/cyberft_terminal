<?php
namespace common\base\traits;

use common\document\Document;
use common\models\User;
use common\models\UserTerminal;
use Yii;
use yii\db\ActiveQuery;

trait ForSigningCountable
{
    public function countForSigning($params = [])
    {
        $this->countMode = true;
        $query = $this->queryForSigning($params);

        return $query->count();
    }

    public function searchForSigning($params = [])
    {
        $this->countMode = false;
        $query = $this->queryForSigning($params);
        return $this->createDataProvider($query, ['dateCreate' => SORT_DESC]);
    }

    protected function queryForSigning($params = null)
    {
        $query = $this->find();

        $terminalId = false;

        if (Yii::$app->user->identity->role !== User::ROLE_ADMIN) {
            // Если пользователь не админ, получаем список доступных ему терминалов
            $terminalId = Yii::$app->user->identity->terminalId;
            if (empty($terminalId)) {
                $terminalId = array_keys(UserTerminal::getUserTerminalIds(Yii::$app->user->id));
            }
            if (!$terminalId) {
                return $query->where('1=0');
            }
        }

        $this->_select = [static::tableName() . '.*'];

        if ($terminalId) {
            $query->where(['document.terminalId' => $terminalId]);
        }

        $types = $this->getSignableDocumentTypes();

        $query->andWhere(['document.status' => Document::STATUS_FORSIGNING])
              ->andWhere('`signaturesRequired` > `signaturesCount`')
              ->andWhere(['signaturesCount' => Yii::$app->user->identity->signatureNumber - 1])
              ->andWhere(['document.type' => $types]);

        // Прочие фильтры
        $this->applyQueryFilters($params, $query);

        $query->select($this->_select);

        $this->applySignaturePermissionFilter($query);

        return $query;
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
    }
}
