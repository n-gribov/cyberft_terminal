<?php

namespace addons\finzip\models\form;

use Yii;
use common\base\Model;


use yii\helpers\ArrayHelper;

class WizardForm extends Model {

	public $docId;
	public $uuid;
	public $subject;
	public $descr;
	public $sender;
	public $recipient;
	public $terminalCode;

	private $maxFilesCount = 10;
	private $_fileList = [];

	public function rules()
	{
		return [
			[['subject', 'descr'], 'required', 'enableClientValidation' => false],
			[['sender', 'recipient'], 'required'],
			[['sender', 'recipient'], 'string', 'length' => [11, 12]],
            ['subject', 'string', 'max' => 255],
            ['descr', 'string', 'max' => 2000, 'tooLong' =>  Yii::t('doc', 'Message cannot be longer than {max} characters')],
		];
	}

	/**
	 * Функция возвращает вектор, содаржащий имена всех safe-атрибутов данной
	 * модели.
	 * CYB-1200. Значение terminalCode не пробрасывается из формы, т.к. данный
	 * атрибут не считается безопасным (для него нет rules). Необходимо в явном
	 * виде указать его как безопасный.
	 * @return array
	 */
	public function safeAttributes()
	{
		return ArrayHelper::merge(parent::safeAttributes(), ['terminalCode']);
	}

	public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'sender'       => Yii::t('doc', 'Sender'),
                'recipient'    => Yii::t('doc', 'Recipient'),
                'terminalCode' => Yii::t('doc', 'Terminal code'),
                'subject'      => Yii::t('doc', 'Subject'),
                'descr'        => Yii::t('doc', 'Description'),
                'fileCount'    => Yii::t('doc', 'Files Attached'),
            ]
        );
    }

    public function validate($attributeNames = null, $clearErrors = true)
	{
		return parent::validate($attributeNames, $clearErrors);
	}

	public function getFiles()
	{
		return $this->_fileList;
	}

	public function getMaxFilesCount()
	{
		return $this->maxFilesCount;
	}

	public function checkMaxFilesCount()
	{
		if (count($this->_fileList) < $this->maxFilesCount) {
			return true;
		} else {
			return false;
		}
	}

    public function addFile($fileName, $path, $size = 0)
	{
		if ($this->checkMaxFilesCount()) {
            $this->_fileList[$fileName] = ['fileName' => $fileName, 'path' => $path, 'size' => $size];

			return true;
		} else {
			$this->addError('file', Yii::t('app/error', 'Limit exceeded the maximum number of uploaded files! You can send no more than {fileCount} files.', ['fileCount' => $this->maxFilesCount]));

			return false;
		}
	}

	public function removeFile($fileName)
	{
		unset($this->_fileList[$fileName]);
	}

	public function hasFiles()
	{
		return !empty($this->_fileList);
	}

	public function clearFiles()
    {
		$this->docId = null;
		$this->_fileList = [];
		/**
		 * @todo clear storage
		 * по идее, файлы должны лежать в temp, который сам очистится когда-нибудь
		 */
	}

    public function getFileCount()
    {
        return count($this->getFiles());
    }

}