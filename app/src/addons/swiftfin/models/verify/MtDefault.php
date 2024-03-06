<?php
namespace addons\swiftfin\models\verify;

use Yii;
use common\base\Model;
use common\base\interfaces\UserVerifyInterface;

class MtDefault extends Model implements UserVerifyInterface
{
    private $_verifyTags = [];
    private $_contentModel;

    public $date;
    public $currency;
    public $sum;

    public function verify($userData)
    {
        if ($this->date !== $userData['32A']['date']) {
            $this->_contentModel->addError('32A');
        }

        if ($this->currency !== $userData['32A']['currency']) {
            $this->_contentModel->addError($this->_verifyTags['currency']);
        }

        if ($this->sum !== $userData['32A']['sum']) {
            $this->_contentModel->addError($this->_verifyTags['sum']);
        }

        if ($this->_contentModel->hasErrors()) {
            return false;
        } else {
            return true;
        }
    }

    private function prepare()
    {
        $this->_verifyTags['date'] = "[32A][date]";
        $this->_verifyTags['currency'] = "[32A][currency]";
        $this->_verifyTags['sum'] = "[32A][sum]";

        $this->date = $this->_contentModel->date;
        $this->currency = $this->_contentModel->currency;
        $this->sum = $this->_contentModel->sum;
    }

    public function getVerifyTags()
    {
        return "'". implode("','", array_values($this->_verifyTags)). "'";
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
}