<?php
namespace common\components\storage;

use common\helpers\ArchiveFileZip;

class FileCollectionZip extends AbstractFileCollection
{
    private $_zip;

    /**
     * Список файлов можно получить из зипа, но он передается отдельно по следующим причинам:
     * 1) При чтении списка из зипа могла потребоваться перекодировка имени файла.
     * Переносить логику перекодировки сюда смысла нет, пусть ее выполняет вызывающий класс.
     * 2) Могут понадобиться не все файлы из архива, а только избранные. Логика отбора также внешняя.
     * Но индекс в списке должен совпадать с фактическим индексом в зипе, то есть список должен формироваться
     * непосредственно из того зипа, который передается сюда.
     * Поэтому и зип передается уже открытым.
     * @param type $list список имен файлов в виде массива [$zip_index => $name]
     * @param ArchiveFileZip $zip
     */
    public function __construct($list, ArchiveFileZip $zip)
    {
        $this->_list = $list;
        $this->_zip = $zip;
        $this->_keys = array_keys($list);
    }

    public function getFileContent($key = null)
    {
        if (is_null($key)) {
            $key = $this->_keys[$this->_index];
        } else if (!$this->checkKey($key)) {
            return false;
        }

        return $this->_zip->getFromIndex($key);
    }

}