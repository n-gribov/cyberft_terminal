<?php

namespace addons\swiftfin\helpers;

/**
 * Description of DictBankReader
 *
 * @author nikolaenko
 */
class DictBankReader
{
    /**
     * @var DictBankInterface $_reader
     */

    private $_reader;

    function __construct(DictBankReaderInterface $reader)
    {
        $this->_reader = $reader;
    }

    public static function getReader($fileList)
    {
        foreach($fileList as $filePath) {
            $lowerCaseName = strtolower(\common\helpers\FileHelper::mb_basename($filePath));
            if ($lowerCaseName == 'fi.dat') {
                return new DictBankReader(new DictBankReaderSWIFT($filePath));
            }

            if ($lowerCaseName == 'sw_list.dbf') {
                return new DictBankReader(new DictBankReaderDBF($filePath));
            }
        }

        return null;
    }

    public function getType()
    {
        return $this->_reader ? $this->_reader->getType() : null;
    }

    public function setReader($reader)
    {
        if ($this->_reader) {
            $this->_reader->close();
            $this->_reader = null;
        }
        $this->_reader = $reader;
    }

    public function openFile($file)
    {
        return $this->_reader ? $this->_reader->openFile($file) : false;
    }

    public function getRecord()
    {
        return $this->_reader ? $this->_reader->getRecord() : null;
    }

    public function close()
    {
        return $this->_reader ? $this->_reader->close() : false;
    }

}