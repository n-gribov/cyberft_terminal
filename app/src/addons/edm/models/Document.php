<?php
namespace addons\edm\models;

use yii\base\Model;
use Yii;
use yii\base\Exception;

/**
 * Class Document
 * @package addons\edm\models
 */
abstract class Document extends Model
{
	use DocumentTrait;

	const LABEL = 'Неопознаный';
	const TYPE  = 'unknown';

	public $filePath;

	/**
	 * данные установленные ранее через setBody
	 * @var string
	 */
	protected $_rawBody;
	/**
	 * данные разобранные при чтении тела документа из parse
	 * @var array
	 */
	protected $rawParsed = [];
	/**
	 * @return null|string
	 */
	abstract public function getRecipient();
	abstract public function validateRecipient($attribute, $params);

	public function init()
	{
		// приращиваем счетчик и берем его значение
		//$this->number = $this->counterIncrement()->getCounter();
	}

	public function rules()
	{
		return [
			['recipient', 'validateRecipient'],
			[['number', 'date', 'recipient'], 'required'],
			['number', 'integer', 'min' => 1, 'max' => 9999999999],
			['date', 'date', 'format' => 'd.M.yyyy'],
		];
	}

	public function attributeLabels()
	{
		return [
			'recipient' => 'Получатель',
			'number'    => '№',
			'date'      => 'от',
		];
	}

	public function attributeReadableLabels()
	{
		return [
			'recipient' => 'Получатель',
			'number'    => 'Номер документа',
			'date'      => 'Дата документа',
		];
	}

	/**
	 * Служит для мапинга на тело документа в формате 1С
	 * @return array
	 */
	public function attributeTags()
	{
		return [
			'number' => 'Номер',
			'date'   => 'Дата',
		];
	}

	/**
	 * @return string
	 */
	public function getBody()
	{
		$data = [];
		$tags = $this->attributeTags();
		foreach ($tags as $k => $v) {
			if (isset($this->$k)) {
				$data[$v] = $this->$k;
			}
		}
		return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	/**
	 * Отдаем неизмененные данные установленные ранее через setBody
	 * @return string
	 */
	public function getRawBody()
	{
		return $this->_rawBody;
	}

	/**
	 * @param string $value
	 * @param string $encoding
	 * @return $this
	 */
	public function setBody($value, $encoding = null)
	{
		if ($encoding && $encoding != 'UTF-8') {
			$value = iconv($encoding, 'UTF-8', $value);
		}

        $this->setRawBody($value, $encoding);
		$this->parse($value, $encoding);

		return $this;
	}

	/**
	 * @param $value
	 * @return $this
	 */
	public function setRawBody($value)
	{
		$this->_rawBody = $value;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBodyReadable()
	{
		return $this->getBody();
	}

	/**
	 * @return string
	 */
	public function getBodyPrintable()
	{
		return $this->getBody();
	}

	/**
	 * @todo fake data
	 * @return string
	 */
	public function getOperationReference()
	{
		return '';
	}

	/**
	 * @param $string
	 * @return $this
	 * @throws Exception
	 */
	abstract protected function parse($string);

	public function __toString()
	{
		return $this->getBody();
	}

	/**
	 * @todo костыль
	 */
	public function getRecipientsProperties()
	{
		$path = Yii::getAlias('@clientConfig/documents/mt/103PaymentOrder.json');
		if (!is_file($path)) {
			return null;
		}

		return @json_decode(file_get_contents($path), JSON_OBJECT_AS_ARRAY);
	}

}