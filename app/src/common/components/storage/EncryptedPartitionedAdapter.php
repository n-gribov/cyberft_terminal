<?php
namespace common\components\storage;

use common\components\Storage;
use Yii;

class EncryptedPartitionedAdapter extends PartitionedAdapter
{
    private $_encryptTerminalId = null;

    public function init()
    {
        parent::init();

        $terminalId = Yii::$app->terminals->getCurrentTerminalId();
        $this->_encryptTerminalId = $terminalId;
    }

    /**
     * Save stream into storage
     *
     * @param stream $readStream Stream to save
     * @param string $filename   File name
     * @return string Storage path
     */
    public function putStream($readStream, $filename = '')
    {
        $content = stream_get_contents($readStream);
        fclose($readStream);
        $fileinfo = $this->putData($content, $filename);

        return $this->modifyFileInfo($fileinfo);
    }

    public function putData($data, $filename = '')
    {
        $requiredData = Yii::$app->xmlsec->encryptData($data);
        $fileinfo = parent::putData($requiredData, $filename);

        return $this->modifyFileInfo($fileinfo);
    }

    public function putFile($path, $filename = '')
    {
        $sourceData = file_get_contents($path);
        $fileinfo = $this->putData($sourceData, $filename);

        return $this->modifyFileInfo($fileinfo);
    }

    public function updateData($relPath, $data)
    {
        $requiredData = Yii::$app->xmlsec->encryptData($data);

        $fileinfo = parent::updateData($relPath, $requiredData);

        return $this->modifyFileInfo($fileinfo);
    }

    private function modifyFileInfo($baseInfo)
    {
        return array_merge($baseInfo, ['isEncrypted' => true]);
    }
}