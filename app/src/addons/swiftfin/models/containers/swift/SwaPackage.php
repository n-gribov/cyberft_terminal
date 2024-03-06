<?php

namespace addons\swiftfin\models\containers\swift;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use addons\swiftfin\models\containers\swift\SwtContainer;

/**
 * @property SwtContainer[] $swtDocuments
 */
class SwaPackage extends Model
{
	public $sourceFile;
	public $source;

	private $_rawData;

	/**
	 * @var SwtContainer[]
	 */
	private $_swtDocuments;

	public static function isTrueFormat($content)
    {
		return (1 < preg_match_all("/(\{\s*1\s*\:)/",$content));
	}

	public function __construct($config = [])
    {
		if (is_string($config)) {
			$config = [
				'sourceFile'	=> $config
			];
		}

		parent::__construct($config);

		if (isset($this->sourceFile)) {
			$this->loadFile($this->sourceFile);
		}
 	}

	/**
	 * @return array
	 */
	public function rules()
    {
		return [
			['swtDocuments', 'validatePackage']
		];
	}

	/**
	 * @param $attribute
	 * @param $options
	 * @return bool
	 */
	public function validatePackage($attribute, $options)
    {
		if (empty($this->$attribute)) {
			$this->addError($attribute, Yii::t('other', 'Swa package is empty'));
			return false;
		}

		$attribute = $this->$attribute;

		$c = count($attribute);
		if ($c === 1) {
			return true;
		}

		/** @var SwtContainer $current */
		$current = null;
		/** @var SwtContainer $prev */
		$prev = $attribute[0];
		for ($i = 1; $i < $c; $i++) {
			$current = $attribute[$i];

			$contentType = $current->getContentType();
			if (empty($contentType) || $current->getContentType() !== $prev->getContentType()) {
				$this->addError(Yii::t('other', "Documents $i and " . $i - 1) . " have different or empty content type");

                return false;
			}

			if ($current->recipient !== $prev->recipient) {
				$this->addError(Yii::t('other', "Documents $i and " . $i - 1) . " have different recipient");

                return false;
			}

			if ($current->sender !== $prev->sender) {
				$this->addError(Yii::t('other', "Documents $i and " . $i - 1) . " have different sender");

                return false;
			}

			if (!isset($current->contentModel->currency) && !isset($prev->contentModel->currency)) {
				continue;
			} else if (!isset($current->contentModel->currency)) {
				$this->addError(Yii::t('other', "Documents $i has no currency"));

                return false;
			} else if ($current->contentModel->currency !== $prev->contentModel->currency) {
				$this->addError(Yii::t('other', "Documents $i and " . ($i - 1) . " have different currency") );

                return false;
			}
		}

		return true;
	}

	/**
	 * @return float
	 */
	public function getSum()
    {
		$total = 0;

        foreach($this->_swtDocuments as $swt) {
			$total += $swt->getSum();
		}

        return $total;
	}


	/**
	 * @return string
	 */
	public function getCurrency()
    {
		return $this->_swtDocuments[0]->getCurrency();
	}

    public function getContentType()
    {
        return $this->_swtDocuments[0]->getContentType();
    }

	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'sourceFile' => Yii::t('other', 'File with SWA-document'),
		]);
	}

	/**
	 * @param type $filePath
	 * @return boolean
	 */
	public function loadFile($filePath)
	{
		$this->sourceFile = Yii::getAlias($filePath);

		if (!file_exists($this->sourceFile)) {
			return false;
		}

		$this->_rawData = file_get_contents($this->sourceFile);

		return $this->loadData($this->_rawData);
	}

	public function loadData($data)
	{
        if (!$this->_rawData) {
            $this->_rawData = $data;
        }

        return $this->parse($data);
	}

	protected function parse($input)
	{
		// разбиение пакета на сообщения по признаку начала собщения "{1:"
		$document = preg_replace('/(\{\s*1\s*\:)/', '%message%${1}', $input);
		$document = explode('%message%', $document);
		array_shift($document); // убрать пустой элемент

		// не обрабатываются пустые файлы
		if (empty($document)) {
			return false;
		}

        foreach($document as $index => $documentBody)
		{
			$newSwtDoc = new SwtContainer();
			$newSwtDoc->loadData($documentBody);
			$this->_swtDocuments[] = $newSwtDoc;
		}

		return true;
	}

	public function getSwtDocuments()
	{
        return $this->_swtDocuments;
	}

	/**
	 * @param SwtContainer[] $swtDocuments
	 */
	public function setSwtDocuments($swtDocuments)
    {
		$this->_swtDocuments = $swtDocuments;
	}

	/**
	 * @return string
	 */
	public function export()
	{
		$results = [];
		if ($this->swtDocuments) {
			foreach ($this->swtDocuments as $swtDoc) {
				$results[] = $swtDoc->export();
			}
		}

		return join('', $results);
	}

	public function save()
    {
		return file_put_contents($this->sourceFile, $this->export());
	}

    public function getRawText()
    {
        return $this->_rawData;
    }

    public function getContentReadable()
    {
    	$content = '';
    	foreach ($this->getSwtDocuments() as $swt) {
    		$content .= "\n". $swt->getContentReadable();
    	}

        return $content;
    }

}
