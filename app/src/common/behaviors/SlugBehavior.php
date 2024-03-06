<?php
namespace common\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

use yii\helpers\BaseInflector;

class SlugBehavior extends Behavior
{
    public $in_attribute = 'title';
    public $out_attribute = 'slug';

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'getSlug'
        ];
    }

    public function getSlug($event)
    {
        $this->owner->{$this->out_attribute} = BaseInflector::slug($this->owner->{$this->in_attribute}, '_');
    }
}