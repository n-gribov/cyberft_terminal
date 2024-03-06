<?php

namespace addons\fileact\models\form;

use Yii;
use common\base\Model;
use yii\helpers\ArrayHelper;

class WizardForm extends Model
{
	public $docId;
	public $uuid;
	public $subject;
	public $descr;
	public $sender;
	public $recipient;
	public $terminalCode;
	public $fileXml;
	public $fileBin;

	private $_fileList;

	public function rules()
	{
		return [
			[['fileXml', 'fileBin'], 'file'],
            ['fileXml', 'safe'],
			[['sender', 'recipient'], 'required'],
			[['sender', 'recipient'], 'string', 'length' => [11, 12]],
		];
	}

	/**
	 * Функция возвращает вектор, содержащий имена всех safe-атрибутов данной
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
		return ArrayHelper::merge(parent::attributeLabels(), [
			'sender'       => Yii::t('doc', 'Sender'),
			'recipient'    => Yii::t('doc', 'Recipient'),
			'terminalCode' => Yii::t('doc', 'Terminal code'),
		]);
	}

	public function validate($attributeNames = null, $clearErrors = true)
	{
		return parent::validate($attributeNames, $clearErrors);
	}

	public function getFiles()
	{
		return $this->_fileList;
	}

    public function getFile($type)
	{
		return isset($this->_fileList[$type]) ? $this->_fileList[$type] : null;
	}

	public function addFile($type, $fileName, $path)
	{
		$this->_fileList[$type] = ['fileName' => $fileName, 'savedPath' => $path];
	}

	public function removeFile($type)
	{
		unset($this->_fileList[$type]);
	}

	public function hasFiles()
	{
		return is_array($this->_fileList) && isset($this->_fileList['bin']);
	}

	public function clearFiles() {

		$this->docId = null;
		$this->_fileList = [];
		/**
		 * @todo clear storage
		 * по идее, файлы должны лежать в temp, который сам очистится когда-нибудь
		 */
	}

	public function getSignaturesRequired()
	{
		return 0;
	}
}