<?php

namespace addons\swiftfin\helpers;

class DictBankReaderSWIFT implements DictBankReaderInterface
{
    private $_file;
    private $_fp;

    private static $addressMarks = [/*189, 224, */323, 358, 463, 638, 673];

    const TYPE = 'SWIFT';

    public function __construct($file = null)
    {
        if (!empty($file)) {
            $this->_file = $file;
            $this->_fp = fopen($file, 'r');
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
        $this->_fp = fopen($file, 'r');

        return !empty($this->_fp);
    }

    public function close()
    {
        if ($this->_fp) {
            fclose($this->_fp);
            $this->_fp = null;

            return true;
        }

        return false;
    }

    public function getRecord()
    {
        $string = null;

        while(!feof($this->_fp) && empty($string)) {
            $string = trim(fgets($this->_fp));
        }

        if (empty($string)) {
            return null;
        }

        $startPos = 0;

        if (substr($string, 0, 3) == 'FIU') {
            $startPos = 3;
        }

        $swiftCode = substr($string, $startPos, 8);
        $branchCode = substr($string, $startPos + 8, 3);

        $startPos += 11;
        $name = substr($string, $startPos, 189 - $startPos);

        $addressChunks = [];
        $count = count(self::$addressMarks);

        for($j = 0; $j < count(self::$addressMarks); $j++) {
            $pos = self::$addressMarks[$j];
            if ($j < $count - 1) {
                $chunk = trim(substr($string, $pos, self::$addressMarks[$j + 1] - $pos));
            } else {
                $chunk = trim(substr($string, $pos));
            }
            if (!empty($chunk)) {
                $addressChunks[] = $chunk;
            }
        }

        return [
            'swiftCode' => $swiftCode,
            'branchCode' => $branchCode,
            'name' => $name,
            'address' => implode(', ', $addressChunks)
        ];
    }

}