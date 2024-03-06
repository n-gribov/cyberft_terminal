<?php
namespace addons\swiftfin\helpers;
use \common\extensions\XBase\Table;

/**
 * Description of DictBankReaderDBF
 *
 * @author nikolaenko
 */
class DictBankReaderDBF implements DictBankReaderInterface
{
    private $_file;
    private $_table;
    private $_recordCount;

    const TYPE = 'CBR';

    public function __construct($file = null)
    {
        if (!empty($file)) {
            $this->openFile($file);
        }
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function openFile($file)
    {
        $this->close();
        $this->_file = $file;
        $this->_table = new Table($file);

        if (!empty($this->_table)) {
            $this->_recordCount = $this->_table->getRecordCount();
            return true;
        }

        return false;
    }

    public function close()
    {
        if ($this->_table) {
            $this->_table->close();
            $this->_table = null;

            return true;
        }

        return false;
    }

    public function getRecord()
    {
        if (!$this->_table) {
            return null;
        }

        $record = $this->_table->nextRecord();
        if (empty($record)) {
            return null;
        }

        $columns = $record->getColumns();

        if (empty($columns)) { // todo: or proper names not set...
            return null;
        }

        return [
            'swiftCode' => $record->kod_bank,
            'branchCode' => $record->kod_branch,
            'name' => $record->name_swift,
            'address' => ''
        ];

    }
}