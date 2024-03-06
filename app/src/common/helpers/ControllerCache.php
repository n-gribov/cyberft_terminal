<?php

namespace common\helpers;

use Yii;

class ControllerCache
{
    private $_key;

    public function __construct($key = null)
    {
        $this->_key = ($key ?: 'common') . '-' . Yii::$app->session->id;
    }

    public function clear()
    {
        \Yii::$app->cache->set($this->_key, []);
    }

    public function set($data)
    {
        \Yii::$app->cache->set($this->_key, $data);
    }

    public function get()
    {
        $entries = \Yii::$app->cache->get($this->_key);

        if (!$entries) {
            $entries = ['entries' => []];
        }

        return $entries;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }
}
