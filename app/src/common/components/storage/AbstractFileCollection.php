<?php
namespace common\components\storage;

abstract class AbstractFileCollection
{
    protected $_list = [];
    protected $_index = false;
    protected $_keys = [];

    public function next()
    {
        $count = count($this->_list);
        if (!$count) {
            return false;
        }

        if ($this->_index === false) {
            $this->_index = 0;
        } else {
            if ($this->_index >= $count - 1) {
                return false;
            }

            $this->_index++;
        }

        return $this->_keys[$this->_index];
    }

    public function getFileList()
    {
        return $this->_list;
    }

    public function getFilename($key = null)
    {
        if (is_null($key)) {
            $key = $this->_keys[$this->_index];
        } else if (!$this->checkKey($key)) {
            return false;
        }

        return $this->_list[$key];
    }

    protected function checkKey($key)
    {
        return array_key_exists($key, $this->_list);
    }

    public function count()
    {
        return count($this->_list);
    }

    public abstract function getFileContent($index = null);

}