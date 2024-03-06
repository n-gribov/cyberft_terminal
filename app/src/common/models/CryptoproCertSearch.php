<?php

namespace common\models;

use addons\fileact\models\FileActCryptoproCertSearch;
use addons\ISO20022\models\ISO20022CryptoproCertSearch;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\base\InvalidArgumentException;

class CryptoproCertSearch extends CryptoproCert
{
    public $terminalName;
    protected static $_table = null;

    public static function getInstance($moduleName)
    {
        // пока в качестве костыля, чтобы в дальнейшем избавиться от разных моделей для каждого модуля
        if ($moduleName == 'ISO20022') {
            return new ISO20022CryptoproCertSearch();
        } else if ($moduleName == 'fileact') {
            return new FileActCryptoproCertSearch();
        } else if ($moduleName == 'VTB') {
            /** @var \addons\VTB\VTBModule $module */
            $module = \Yii::$app->addon->getModule($moduleName);
            if ($module) {
                return $module->getCryptoProCertModel(true);
            }

            throw new InvalidArgumentException('VTB module not found');
        }

        return new static();
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['terminalId', 'ownerName', 'certData',
                'keyId', 'serialNumber', 'senderTerminalAddress', 'terminalName', 'status'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $this->applyExtFilters($query);

        $cryptoKeysTableName = static::tableName();

        $query->andFilterWhere(['like', "{$cryptoKeysTableName}.terminalId", $this->terminalId])
            ->andFilterWhere(['like', 'ownerName', $this->ownerName])
            ->andFilterWhere(['like', 'certData', $this->certData])
            ->andFilterWhere(['like', 'serialNumber', $this->serialNumber])
            ->andFilterWhere(['=', "{$cryptoKeysTableName}.status", $this->status])
            ->andFilterWhere(['like', 'keyId', $this->keyId]);

        return $dataProvider;
    }

    public function applyExtFilters($query)
    {
        // Запрос по связанной таблице терминалов
        $terminalTableName = Terminal::tableName();
        $query->joinWith([$terminalTableName]);

        $query->andFilterWhere(['like', "{$terminalTableName}.terminalId", $this->terminalName]);
    }

}
