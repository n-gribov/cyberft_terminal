<?php

namespace addons\finzip\models;

use common\base\BaseType;
use common\helpers\FileHelper;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use ZipArchive;

class FinZipType extends BaseType
{
    const TYPE = 'FINZIP';
    const FINZIP_MESSAGE_FILE = 'FINZIP_message.txt';

    public $subject;
    public $descr;
    public $zipStoredFileId;
    public $fileCount;
    public $sender;
    public $recipient;
    public $attachmentUUID;

     /**
     * Load data from zip file
     *
     * @param string $id id of stored zip file
     * @return boolean
     */
    public function loadFromFile($id)
    {
        $path = Yii::$app->storage->get($id)->getRealPath();

        if (!is_null($path)) {
            $this->zipStoredFileId = $id;
            $this->getZipFiles($path);

            return true;
        }

        return false;
    }

    public function loadFromString($str, $isFile = false, $encoding = null)
    {
        $path = Yii::getAlias('@temp/') . FileHelper::uniqueName() . '.zip';

        if (file_put_contents($path, $str)) {
            $this->getZipFiles($path);
            unlink($path);
        }
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getModelDataAsString()
    {
        $zipData = $this->getZipAsString();
        if ($zipData === false) {
            \Yii::warning('Failed to get zip content');

            return '';
        }

        return $zipData;
    }

    /**
     * Get Zip as binary
     *
     * @return mixed Return binary zip or FALSE
     */
    protected function getZipAsString()
    {
        if (is_null($this->zipStoredFileId)) {
            return false;
        }

        return Yii::$app->storage->decryptStoredFile($this->zipStoredFileId);
    }

    /**
     * Get files from zip
     *
     * @param $path
     * @return bool
     * @throws Exception
     */
    protected function getZipFiles($path)
    {
        try {
            $zip = new ZipArchive();
            $result = $zip->open($path);

            if ($result !== true) {
                throw new ErrorException($result);
            }

            $this->fileCount = $zip->numFiles;

            return $this->parseMessage($zip->getFromName(self::FINZIP_MESSAGE_FILE));

        } catch (ErrorException $ex) {
            Yii::info('Could not open zip file, error code: ' . $ex->getMessage());
            $this->addError('zip', Yii::t('doc', 'Open ZIP error: {error}',
                ['error' => $ex->getMessage()]));
        }

        return false;
    }

    /**
     * Parse text into subject and descr
     *
     * @param $messageString
     * @return bool
     */
    protected function parseMessage($messageString)
    {
        $message = explode("\n", $messageString);

        if (count($message)) {
            $this->subject = trim(array_shift($message));

            $this->descr = '';
            while (count($message)) {
                if ($this->descr) {
                    $this->descr .= "\n";
                }

                $this->descr .= trim(array_shift($message));
            }
        }

        return true;
    }

	public function getSearchFields()
	{
		return [
			'sender' => $this->sender,
			'receiver' => $this->recipient,
			'subject' => $this->subject,
			'descr' => $this->descr,
            'attachmentUUID' => $this->attachmentUUID,
		];
	}

    public function getMessage()
    {
        return $this->subject . "\n" . $this->descr;
    }

}