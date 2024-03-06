<?php
namespace addons\swiftfin\models\documents\mt\mtUniversal;

use yii\base\ErrorException;
use yii\base\Model;
use yii\widgets\ActiveForm;

/**
 * Class Entity
 * @property array $scheme
 * @package addons\swiftfin\models\documents\mt\mtUniversal
 */
abstract class Entity extends Model implements EntityInterface
{
	const ENTITY_NAMESPACE = '\addons\swiftfin\models\documents\mt\mtUniversal\\';
	const STATUS_MANDATORY = 'M';
	const STATUS_OPTIONAL  = 'O';
	const LINE_BREAK       = "\r\n";
	const INLINE_BREAK     = "\r\n";

	//Заглушка для определения принадлежности полей формы к типам данных
	//[choice, collection, default]
	public $dataType = 'default';

	/**
	 * @var string
	 */
	public $label;
	/**
	 * @var string
	 */
	public $fullLabel;
	/**
	 * @var string
	 */
	public $status;
	/**
	 * @var Entity
	 */
	public $parent;
	/**
	 * @var bool
	 */
	public $disableLabel = false;
	/**
	 * Описание схемы составных частей, служит для отображения
	 * @var array
	 */
	protected $_scheme = [];
	/**
	 * @var mixed
	 */
	protected $_value;
	/**
	 * @var string
	 */
	protected $_name;
	/**
	 * @var string
	 */
	protected $_type;
	/**
	 * @var bool
	 */
	protected $_isNameRnd = false;
	/**
	 * @var int
	 */
	protected $printableNamePadSize = 6;

	public function init()
    {
		/**
		 * Нам необходимо наличие name у сущности в любом случае
		 * т.к. это структуро образующее свойство, вводим данное правило
		 * @todo рефайторинг на тему генерации случайного имени, в каком-то месте в данном случае происходит сбой в струтре и ключ в массиве не совпадает с именем
		 */
		if (!isset($this->_name)) {
			$this->_name      = $this->generateName();
			$this->_isNameRnd = true;
		}
	}

	/**
	 * Функция рекурсивно поднимает наверх до корневого элемента и возвращает ссылку на объект
	 * @todo оптимизировать, данные метод часто вызывается и при таком подходе дорог в выполнении
	 * @return $this|Entity
	 */
	public function getRoot()
    {
		if (isset($this->parent)) {
			return $this->parent->getRoot();
		} else {
			return $this;
		}
	}

	/**
	 * @return string
	 */
	public function getName()
    {
		return $this->_name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
    {
		$this->_name      = $name;
		$this->_isNameRnd = false;
	}

	/**
	 * @return boolean
	 */
	public function getIsNameRnd()
    {
		return $this->_isNameRnd;
	}

	/**
	 * Set real full entity value
	 * @param $value
	 * @return mixed
	 */
	public function setValue($value)
    {
		$this->_value = $value;

		return $this;
	}

//  в связи с потребностью ввода транслитерации, временно закоменчу
//	public function getValue() {
//		return $this->_value;
//	}

	public function unsetValue()
    {
		$this->_value = null;
	}

	/**
	 * @return string
	 */
	public function getType()
    {
		return $this->_type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
    {
		$this->_type = $type;
	}

	/**
	 * @inheritdoc
	 */
	public function formName()
    {
		if (is_object($this->parent)) {
			return $this->parent->formName().'['.$this->name.']';
		} else {
			return parent::formName();
		}
	}

	/**
	 * @return array
	 */
	public function schemePath()
    {
		$names = [];
		if (is_object($this->parent)) {
			$names = (array)$this->parent->schemePath();
		}
		$names[] = $this->getName();

        return $names;
	}

	/**
	 * @return string
	 */
	public function getIdentifier()
    {
		return implode('', $this->schemePath());
	}


	/**
	 * @return array
	 */
	public function attributeLabels()
    {
		return [
			'value' => $this->label
		];
	}

	/**
	 * @param array $scheme
	 * @return $this
	 * @throws ErrorException
	 */
	public function setScheme(&$scheme)
    {
		if ($this->_scheme) {
			throw new ErrorException('You can\'t change instance scheme');
		}
		$this->_scheme = $scheme;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getScheme()
    {
		return $this->_scheme;
	}


	/**
	 * @param null $form
	 * @return string
	 */
	public function toHtmlForm($form = null)
    {
		if (!$form) {
			$form = new ActiveForm();
		}
		$html = '';
		foreach ($this->_model as $v) {
			$html .= $v->toHtmlForm($form);
		}

        return $html;
	}

	/**
	 * @return string
	 */
	protected function generateName()
    {
		if (isset($this->label)) {
			$base = $this->label;
		} elseif (isset($this->fullLabel)) {
			$base = $this->fullLabel;
		} else {
			$base = (string)mt_rand(0, 999999);
		}

        return substr(hash('sha256', $base), 0, 4);
	}

    public function getReadableErrors()
    {
        $errors = $this->errors;
        $out  = [];

        foreach($errors as $field => $content) {
            $out[] = $field . ': ' . $this->recursiveCollect($content);
        }

        return implode(";\n", $out);
    }

    private function recursiveCollect($input)
    {
        if (!is_array($input)) {
            return $input;
        }

        $out = [];
        foreach($input as $entry) {
            $out[] = $this->recursiveCollect($entry);
        }

        return implode(";\n", $out);
    }

}