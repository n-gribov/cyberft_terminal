<?php

namespace common\extensions;

use yii\base\Exception;

class Dbf {
    //Private Properties
    private $raw, $version, $modifyDate, $recsNum, $fullHeaderLen, $recLen,
            $fieldsNum, $headerSize = 32, $src, $header, $recsCount;

    /**
     * @param string $src
     * @throws Exception
     */
    protected function initFile($src)
    {
        if (file_exists($src) && (strcasecmp(substr($src, -4), '.dbf') == 0)) {
            //Read the File
            $this->src = fopen($src, 'rb');
            if (!$this->src) {
                throw new Exception('Cannot read DBF file');
            }
        } else {
            throw new Exception('Not a valid DBF file');
        }
    }

    /**
     * @param resource $src
     */
    protected function initResource($src)
    {
        $this->src = $src;
    }

    /**
     * @param string|resource $src
     * @throws Exception
     */
    public function __construct($src)
    {
        //Check DBF File
        if (is_string($src)) {
            $this->initFile($src);
        } else if (is_resource($src)) {
            $this->initResource($src);
        } else {
            throw new Exception('Broken resource type');
        }

        $this->raw = fread($this->src, $this->headerSize);
        //Check DBF Version
        if (!((ord($this->raw[0]) == 3) || (ord($this->raw[0]) == 131) || (ord($this->raw[0]) == 245)
             || (ord($this->raw[0]) == 139))
        ) {
            throw new Exception('Not a valid DBF file!');
        }
        $arrHeaderHex = [];
        for ($i = 0; $i < $this->headerSize; $i++) {
            $arrHeaderHex[$i] = str_pad(dechex(ord($this->raw[$i])), 2, "0", STR_PAD_LEFT);
        }
        //Version of DBF
        $this->version = hexdec($arrHeaderHex[0]);
        //Date of Last Modify
        $this->modifyDate = hexdec($arrHeaderHex[3]) . '.' . hexdec($arrHeaderHex[2]) . '.' . hexdec($arrHeaderHex[1]);
        //Number of Records
        $this->recsNum = hexdec($arrHeaderHex[7] . $arrHeaderHex[6] . $arrHeaderHex[5] . $arrHeaderHex[4]);
        //Full Header's Length (Header Size + Field Descriptor)
        $this->fullHeaderLen = hexdec($arrHeaderHex[9] . $arrHeaderHex[8]);
        //Record's Length
        $this->recLen = hexdec($arrHeaderHex[11] . $arrHeaderHex[10]);
        //Number of Fields
        $this->fieldsNum = (($this->fullHeaderLen - 1) / $this->headerSize) - 1;
        //Record's Count
        $this->recsCount = $this->recsNum;
        //Field's Description
        $i = 0;
        while ($i++ < $this->fieldsNum) {
            $this->header[$i]['name'] = trim($this->zeroCodeCut(fread($this->src, 11)));
            $this->header[$i]['type'] = $this->zeroCodeCut(fread($this->src, 1));
            $this->header[$i]['mem_addr'] = 
                dechex(ord(fread($this->src, 1)))
                . dechex(ord(fread($this->src, 1)))
                . dechex(ord(fread($this->src, 1)))
                . dechex(ord(fread($this->src, 1)));
            $this->header[$i]['field_len'] = ord(fread($this->src, 1));
            $this->header[$i]['deciminal_len'] = ord(fread($this->src, 1));
            fseek($this->src, 14, SEEK_CUR);
        }
        //Shift one byte (Mark of delete)
        fseek($this->src, 1, SEEK_CUR);
    }

    function version()
    {
        return $this->version;
    }

    function modifyDate()
    {
        return $this->modifyDate;
    }

    function recsNum()
    {
        return $this->recsNum;
    }

    function fullHeaderLen()
    {
        return $this->fullHeaderLen;
    }

    function recLen()
    {
        return $this->recLen;
    }

    function fieldsNum()
    {
        return $this->fieldsNum;
    }

    function header()
    {
        return $this->header;
    }

    //Delete Empty
    function zeroCodeCut($str)
    {
        $i = 0;
        while (isset($str[++$i]) && ord($str[++$i]) != 0);

        return substr($str, 0, $i);
    }

    //Get Next Record
    function nextRec($trim = true)
    {
        if (!$this->checkNextRec()) {
            return 0;
        }
        $this->recsCount--;
        fseek($this->src, 1, SEEK_CUR);
        $i = 0;
        while ($i++ < $this->fieldsNum) {
            if ($trim) {
                $rec[$this->header[$i]['name']] = trim(iconv('CP866', 'UTF-8', fread($this->src, $this->header[$i]['field_len'])));
            } else {
                $rec[$this->header[$i]['name']] = iconv('CP866', 'UTF-8', fread($this->src, $this->header[$i]['field_len']));
            }
        }
        return $rec;
    }

    //Check Next Record
    function checkNextRec()
    {
        if ($this->recsCount <= 0) {
            fclose($this->src);
            return 0;
        }
        return 1;
    }

    //Set Next Record
    function setNextRec($recNum)
    {
        if ((feof($this->src)) or !($recNum > 0)) {
            return 0;
        }
        $this->recsCount = $this->recsNum - $recNum;
        fseek($this->src, $recNum * $this->recLen, SEEK_CUR);
        return 1;
    }
}
