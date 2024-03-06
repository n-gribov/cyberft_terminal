<?php
namespace common\db;

use yii\db\ActiveQuery as yiiActiveQuery;

class ActiveQuery extends yiiActiveQuery
{
    public $parentModel = null;
    public $parentModelParams = null;

    public function count($q = '*', $db = null)
    {
        if (!$this->parentModel || $this->parentModel->countMode) {
            return parent::count($q, $db);
        }
        $this->parentModel->countMode = true;
        $query = $this->parentModel->buildQuery($this->parentModelParams);

        $result = $query->count($q, $db);
        $this->parentModel->countMode = false;

        return $result;
    }

}
