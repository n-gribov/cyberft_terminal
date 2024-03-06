<?php

namespace addons\swiftfin\models;

use Yii;
use common\base\Model;
use common\base\interfaces\UserVerifyInterface;
use yii\helpers\ArrayHelper;

class SwiftFinUserVerify extends Model implements UserVerifyInterface
{
    private $_verifyTags = [];
    private $_contentModel;
    public $collection = [];

    public function verify($userData)
    {
        foreach($this->collection as $id => $value) {
            if ($value !== ArrayHelper::getValue($userData, $id)) {
                $this->_contentModel->addError($this->_verifyTags[$id]);
            }
        }

        return ! $this->_contentModel->hasErrors();
    }

    protected function prepare()
    {

    }

    public function getVerifyTags()
    {
        return "'" . implode("','", array_values($this->_verifyTags)) . "'";
    }

    public function getContentModel()
    {
        return $this->_contentModel;
    }

    public function setContentModel($contentModel)
    {
        $this->_contentModel = $contentModel;
        $this->prepare();
    }

    public function prepareVerifyTags($verifyFields)
    {
        foreach($verifyFields as $item) {
            $value = $this->_contentModel->getValueByPath($item);

            // Поля с пустыми значениями пропускаются
            if (empty($value)) {
                continue;
            }

            // Формирование строки-идентификатора для верификации
            $itemString = implode('.', $item);

            // Формирование значения для верификации
            $itemVerify = '';

            foreach($item as $element) {
                $itemVerify .= '[' . $element . ']';
            }

            $this->_verifyTags[$itemString] = $itemVerify;

            $this->collection[$itemString] = $value;
        }
    }

    public function getSettings() {
        return Yii::$app->settings->get('swiftfin:swiftfin');
    }

}
