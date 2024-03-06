<?php

namespace common\settings;

use Yii;
use common\base\Model;

/**
 * Description of BaseSettings
 *
 * @author fuzz
 */
class BaseSettings extends Model
{
    const CODE_DELIMITER = ':';

    protected $_dataModel = null;

    public $terminalId = null;

    public function init()
    {
        // Данная проверка добавлена из-за использования настроек в Addon::registerResources
        // при установке терминала возникает ошибка, связанная с отсутствием таблицы settings
        if (Yii::$app->db->schema->getTableSchema(SettingsAR::tableName())) {
            
            $query = SettingsAR::find()->where(['code' => $this->code]);

            $query->andWhere(['terminalId' => $this->terminalId]);

            $this->_dataModel = $query->one();

            if (!empty($this->_dataModel)) {
                $data = unserialize($this->_dataModel->data);
                $this->setAttributes($data);
            }
        }
    }

    public function rules()
    {
        return [
            [$this->attributes(), 'safe']
        ];
    }

    public function getCode()
    {
        $reflection = new \ReflectionClass($this);
        $code       = preg_replace(
            '/Settings$/', '', $reflection->getShortName()
        );
        $namespace  = $reflection->getNamespaceName();

        $moduleClass = '';
        $pattern = '/^(addons|common\\\modules)\\\([^\\\]+)\\\settings$/';
        if (preg_match($pattern, $namespace, $matches)) {
            $moduleClass = "{$matches[1]}\\{$matches[2]}\\" 
                . \yii\helpers\Inflector::camelize($matches[2])
                . 'Module';
        }
        if (!empty($moduleClass)) {
            $code = $moduleClass::getInstance()->id . static::CODE_DELIMITER . $code;
        }
        
        return $code;
    }

    public function save($runValidation = true)
    {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        if (is_null($this->_dataModel)) {
            $this->_dataModel = new SettingsAR(['code' => $this->code]);
            if ($this->terminalId) {
                $this->_dataModel->terminalId = $this->terminalId;
            }
        }

        $this->_dataModel->data = serialize($this->attributes);

        // Сохранить модель в БД
        return $this->_dataModel->save();
    }

    public function getDataModel()
    {
        return $this->_dataModel;
    }

}