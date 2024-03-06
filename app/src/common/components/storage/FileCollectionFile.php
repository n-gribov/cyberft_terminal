<?php
namespace common\components\storage;

class FileCollectionFile extends AbstractFileCollection
{
    /**
     * @param type $list список имен файлов в виде массива [$index => $path]
     */
    public function __construct($list)
    {
        $this->_list = $list;
        $this->_keys = array_keys($list);
    }

    public function getFileContent($key = null)
    {
        // тут надо читать файл с диска, но пока нет необходимсти в реализации этого метода

        throw new \Exception('Method getFilecontent is not implemented in FilecollectionFile');
    }

}