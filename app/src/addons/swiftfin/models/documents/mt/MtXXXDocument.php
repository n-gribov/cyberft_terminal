<?php
namespace addons\swiftfin\models\documents\mt;

use addons\swiftfin\SwiftfinModule;
use Yii;
use yii\base\Exception;
use yii\helpers\StringHelper;

/**
 * Universal MT Document MEGA MODEL
 *
 * @author fuzz
 */
class MtXXXDocument extends MtBaseDocument
{
	/**
	 * @var string
	 */
	public $label;
	/**
	 * Отметка о том, стоит ли пытаться построить форму по модели
	 * @var bool
	 */
	public $formable = false;
	/**
	 * Пометка, что документ не должен отображаться в интерфейсах системы
	 * @var bool
	 */
	public $hidden = false;

	/**
	 * @var string numeric
	 */
	protected $_type;
	protected $_attributes;
	protected $_attributeLabels;
	protected $_attributeTags;
	protected $_attributeFields;

	/**
	 * Реальные значения документа в привязке к тэгам
	 * @var array
	 */
	protected $_tags = [];

	/**
	 * Предполагаем, что в данном тэге всегда будет содержаться нужный нам указатель
	 * @todo держать на контроле в связке с swtContainer
	 *
	 * @return string|null
	 */
	public function getOperationReference()
    {
		if ($this->hasTag('20')) {
			return $this->getTag('20');
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function getCurrency()
    {
		if ($this->hasTag('32A')) {
			return $this->parse32A()['currency'];
		}

        return null;
	}

	/**
	 * @return float
	 */
	public function getSum()
    {
		if ($this->hasTag('32A')) {
			return (float)$this->parse32A()['sum'];
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function getDate()
    {
		if ($this->hasTag('32A')) {
			return $this->parse32A()['date'];
		}

		return null;
	}

	/**
	 * @param $data
	 * @return array|bool
	 */
	public static function parseMtString($data)
	{
		$data = trim($data, '-');
		$data = explode(':', $data);
		array_shift($data);
		$tags = $values = [];
		foreach ($data as $k => $v) {
			$v = trim($v);
			if ($k % 2) {
				array_push($values, $v);
			} else {
				array_push($tags, $v);
			}
		}

		if (count($values) != count($tags)) {
			return false;
		}

		return array_combine($tags, $values);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 * @throws \yii\base\UnknownPropertyException
	 */
	public function __set($name, $value)
    {
		if ($this->hasAttribute($name)) {
			$this->setAttribute($name,$value);

			return;
		}

        return parent::__set($name, $value);
	}

	/**
	 * @param string $name
	 * @return mixed
	 * @throws \yii\base\UnknownPropertyException
	 */
	public function __get($name)
    {
		if ($this->hasAttribute($name)) {
			return $this->getAttribute($name);
		}

        return parent::__get($name);
	}

	/**
	 * @return mixed
	 */
	public function getType()
    {
		return $this->_type;
	}

	/**
	 * @param string $value
	 * @return $this
	 */
	public function setType($value)
    {
		$this->_type = $value;
		$this->attributes();
		$this->attributeTags();
		$this->attributeLabels();
		$this->attributeFields();

        return $this;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 * @throws Exception
	 */
	public function setTag($name,$value)
    {
		if ($this->hasTag($name)) {
			$this->_tags[$name] = $value;
		} else {
			throw new Exception('Unconfigured tag '.$name);
		}

		return $this;
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getTag($name)
    {
		if ($this->hasTag($name) && isset($this->_tags[$name])) {
			return $this->_tags[$name];
		}

		return null;
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public function hasTag($name)
    {
		// если работаем без тэгов вообще, то согласны на любое именование тэга
		if (empty($this->_attributeTags)) {
			return true;
		}

        return in_array($name,$this->_attributeTags);
	}

	/**
	 * @param $arr
	 * @return $this
	 */
	public function setTags($arr)
    {
		foreach ($arr as $k => $v) {
			$this->setTag($k, $v);
		}

        return $this;
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getAttribute($name)
    {
		return $this->getTag($this->getAttributeTag($name));
	}

	/**
	 * @param $name
	 * @param $value
	 * @return $this
	 */
	public function setAttribute($name,$value)
    {
		$this->setTag($this->getAttributeTag($name), $value);

		return $this;
	}

	/**
	 * @param $attribute
	 * @return bool
	 */
	public function hasAttribute($attribute)
    {
		return in_array($attribute, $this->_attributes);
	}

	/**
	 * @param array $arr
	 * @return $this
	 */
	public function setAttributes($arr,$safeOnly = true)
    {
		foreach ($arr as $k => $v) {
			$this->setAttribute($k, $v);
		}

		return $this;
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function getAttributeTag($name)
    {
		if (empty($this->_attributeTags)) {
			return $name;
		}

		return isset($this->_attributeTags[$name]) ? $this->_attributeTags[$name] : null;
	}

	/**
	 * @param $name
	 * @return mixed|null
	 */
	public function getTagAttribute($name)
    {
		if (($k = array_search($name, (array)$this->_attributeTags))) {
			return $k;
		}

        return null;
	}

	/**
	 * @return array
	 * @throws \yii\base\Exception
	 */
	public function attributes() {
		if (!isset($this->_attributes)) {
			$this->_attributes = SwiftfinModule::getInstance()->mtDispatcher->attributes($this->getType());
		}

		return $this->_attributes;
	}

	/**
	 * @return array
	 */
	public function rules()
    {
		return [];
	}

	/**
	 * @return array
	 * @throws \yii\base\Exception
	 */
	public function attributeLabels()
    {
		if (!isset($this->_attributeLabels)) {
			$this->_attributeLabels = SwiftfinModule::getInstance()->mtDispatcher->attributeLabels($this->getType());
		}

		return $this->_attributeLabels;
	}

	/**
	 * @return mixed
	 * @throws \yii\base\Exception
	 */
	public function attributeFields()
    {
		if (!isset($this->_attributeFields)) {
			$this->_attributeFields = SwiftfinModule::getInstance()->mtDispatcher->attributeFields($this->getType());
		}

        return $this->_attributeFields;
	}

	/**
	 * @param string $name
	 * @return string|null
	 */
	public function attributeField($name)
    {
		return isset($this->_attributeFields[$name]) ? $this->_attributeFields[$name] : null;
	}

	/**
	 * @return array
	 * @throws \yii\base\Exception
	 */
	public function attributeTags()
    {
		if (!isset($this->_attributeTags)) {
			$this->_attributeTags = SwiftfinModule::getInstance()->mtDispatcher->attributeTags($this->getType());
		}

		return $this->_attributeTags;
	}

	/**
	 * @return array
	 */
	public function attributeReadableTags()
    {
		return $this->attributeTags();
	}

	/**
	 * @param null $attributeNames
	 * @param bool $clearErrors
	 * @return bool
	 */
	public function validate($attributeNames = null, $clearErrors = true)
	{
		parent::validate($attributeNames, $clearErrors);

		if (is_null($attributeNames)){
			$this->validateExternal();
		}

		return !$this->hasErrors();
	}


	/**
	 * @inheritdoc
	 */
	public function preprocessAttributes()
	{
		foreach($this->_tags as $k => $v) {
			$v = StringHelper::truncate($v, static::MAX_STR_LENGTH, '');
			$v = $this->transliterateString($v);
			$this->setTag($k,$v);
		}
	}

	/**
	 * @param bool $readableFormat
	 * @return string
	 */
	public function packData($readableFormat=false)
    {
		$result = '';

		foreach ($this->_tags as $k => $v) {
			$v = trim($v);
			if (!empty($v)) {
				$result.= ":{$k}:{$v}" . self::EOL;
			}
		}
		$result = trim($result);

		return !empty($result) ? trim($result) . self::EOL . '-' : '';
	}

	/**
	 * Загрузить данные из строки в MT формате
	 * @param $data
	 * @return bool
	 */
	protected function loadFromString($data)
	{
		$data = self::parseMtString($data);
		if (!$data) {
			$this->addError('body', Yii::t('doc/mt', 'Invalid data format'));
			return false;
		}

		foreach($data as $tag => $value) {
			try {
				$this->setTag($tag,$value);
			} catch (Exception $e) {
				$this->addError('body', Yii::t('doc/mt', 'Unsupported tag for '.$this->getType().' ":{tag}:"', ['tag' => $tag]));
			}
		}

		return true;
	}


	/**
	 * Валидация МТ документа с помощью внешнего перлового компонента
	 *
	 * @return boolean
	 */
	public function validateExternal()
	{
		$template = <<<EOT
From: %s
To: %s
Content-Type: swift/%s
Begin
%s
End
EOT;
		// проверяем наличие перлового валидатора
		if (
			!is_file(Yii::getAlias($this->validatorPath) . '/lib/CyberFT/SWIFT/Types/MT' . $this->type . '.pm')
			|| in_array($this->type, [950, 940, 300, 320])
		) {
			return true;
		}

		/**
		 * Подставляются тестовые данные отправителя-получателя,
		 * чтобы не замешивать здесь логику с родительскими объектами
		 * Dependency Inversion or Death mother fucker!
		 */
		$envelope = sprintf($template,
			Yii::$app->terminals->address,
			'TESTRUM3A001',
			$this->type,
			base64_encode($this->packData())
		);

		$cwd = Yii::getAlias($this->validatorPath);

		$descriptorSpec = [
			0 => ['pipe', 'r'],
			1 => ['pipe', 'w'],
		];

		$process = proc_open(
			"/usr/bin/perl {$cwd}/swift_validate.pl",
			$descriptorSpec,
			$pipes,
			$cwd
		);

		$result = '';
		if (is_resource($process)) {
			fwrite($pipes[0], $envelope);
			fclose($pipes[0]);

			$result = trim(stream_get_contents($pipes[1]));
			fclose($pipes[1]);

			proc_close($process);
		}

		if (1 == $result[0]) {
			$lines = explode(PHP_EOL, $result);
			array_shift($lines);

			foreach ($lines as $line) {
				$line = explode(':', $line);
				$message = trim ($line[0]);
				$field = 'body';
				$tag = '';
				if (!empty($line[1])) {
					if ($tag = trim($line[1])) {
						$field = $this->getTagAttribute($tag);
					}
				}

				$this->addError($field,
					Yii::t('doc/mt', $message)
					. ( !empty($tag)
						? ' "' . ":{$tag}: " . $this->getAttributeLabel($field) . '"'
						: ''
					)
				);
			}
			return false;
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function __toString()
    {
		return $this->packData();
	}

	/**
	 * @return null
	 */
	protected function parse32A()
    {
		if (preg_match(
			'/'
				. '(?P<date>[0-9]{6,8})'
				. '(?P<currency>' . implode('|', array_keys(self::$currencyIsoCodes)) . ')'
				. '(?P<sum>[0-9,.]{1,15})'
			. '/',
			$this->getTag('32A'),
			$found
		)) {
			return $found;
		}

		return null;
	}

}