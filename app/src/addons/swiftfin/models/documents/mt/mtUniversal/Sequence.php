<?php
namespace addons\swiftfin\models\documents\mt\mtUniversal;

use \addons\swiftfin\models\documents\mt\widgets\Sequence as Widget;
use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use addons\swiftfin\models\documents\mt\mtUniversal\Tag;

use yii\base\Exception;

class Sequence extends Entity
{
	public $disableContainer = false;

    /**
	 * Структура данных
	 * @var Sequence[] | Tag[]
	 */
	protected $_model = [];

	public function init()
	{
		parent::init();

        $this->prototyping();
	}

	/**
	 * @return Entity[]
	 */
	public function getModel()
	{
		return $this->_model;
	}

	/**
	 * @param string $name
	 * @return Entity | Sequence | Tag
	 */
	public function getNode($name)
	{
		if (isset($this->_model[$name])) {
			return $this->_model[$name];
		}

		return null;
	}

	/**
	 * @return Entity[]
	 */
	public function getValue()
	{
		return $this->_model;
	}

	/**
	 * @param $value
	 * @return $this
	 * @throws Exception
	 */
	public function setValue($value)
	{
		if (is_array($value)) {
			foreach ($value as $k => $v) {
				if (isset($this->_model[$k])) {
					$this->_model[$k]->setValue($v);
				}
			}
		} else {
			throw new Exception('Wrong value type ' . var_export($value, true));
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function hasErrors($attribute = null)
	{
		if (parent::hasErrors($attribute)) {
			return true;
		} else {
			foreach ($this->_model as $item) {
				if ($item->hasErrors()) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function validate($attributeNames = null, $clearErrors = true)
	{
		$r = true;
		foreach ($this->_model as $item) {
			if (!$item->validate()) {
				$r = false;

                break;
			}
		}

		if (!$r || !parent::validate($attributeNames, $clearErrors)) {
			return false;
		}

		return true;
	}

     /*
      * (СYB-4368) Функция для перевода самого глубокого из вложенных "массивов" ошибок
      * в строку. Иначе не будет работать вывод ошибок через ActiveForm::errorSummary
      */
    private function deepestArrayToString(array $arr, $k = '0')
    {
        return (
            is_array(current($arr))
        ) ? $this->deepestArrayToString(current($arr), $k) : "$k: " . array_values($arr)[0];
    }

	/**
	 * @inheritdoc
	 */
	public function getErrors($attribute = null, $depth = 0)
	{
		$errors = [];
		foreach ($this->_model as $k => $item) {
            if ($item->hasErrors()) {
                $depth++;
                $itemErrors = $item->getErrors(null, $depth);

                if ($depth == 1){
                   $itemErrors = [$this->deepestArrayToString($itemErrors, $k)];
                }

                if (isset($itemErrors['value'])) {
                    $errors[$k] = join("\n", $itemErrors['value']);
                } else {
                    $errors[$k] = $itemErrors;
                }
            }
		}

        return array_merge(parent::getErrors($attribute), $errors);
	}

	/**
	 * @param Form $form
	 * @return string
	 * @throws \Exception
	 */
	public function toHtmlForm($form = null)
    {
		return Widget::widget([
			'model' => $this,
			'form'  => $form
		]);
	}

	/**
	 * @param bool $translitDecode
	 * @return string
	 */
	public function toReadable($translitDecode = false)
	{
		$rows = [];
		$n = 0;
		$s = 0;

		foreach ($this->_model as $item) {
			$substr = $item->toReadable($translitDecode);
			if (strlen($substr) === 0) {
				continue;
			}
			// считаем сервисные сущности
			if (isset($item->service) && $item->service) {
				$s++;
			}

			$rows[] = $substr;
			$n++;
		}

		/**
		 * если кол-во сервисных сущностей совпадает с общим кол-вом тэгов, то
		 */
		if ($n === $s) {
			return '';
		}

		return implode(null, $rows);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		$rows = [];
		$n = 0;
		$s = 0;
		foreach ($this->_model as $item) {
			$substr = (string) $item;
			if (strlen($substr) === 0) {
				continue;
			}
			// считаем сервисные сущности
			if (isset($item->service) && $item->service) {
				$s++;
			}

			$rows[] = $substr;
			$n++;
		}

		/**
		 * если кол-во сервисных сущностей совпадает с общим кол-вом тэгов, то
		 */
		if ($n === $s) {
			return '';
		}

		return implode(self::LINE_BREAK, $rows);
	}

	/**
	 * @param array $scheme
	 * @param array $options
	 * @return $this
	 */
	protected function setModel($scheme, $options = [])
	{
		$model = $this->createModelByScheme($scheme, $options);

		if (isset($scheme['name'])) {
			$this->_model[$scheme['name']] = $model;
		} else if (isset($model->name)) {
			$this->_model[$model->name] = $model;
		} else {
			$this->_model[] = $model;
		}

		return $this;
	}

	/**
	 * @param       $scheme
	 * @param array $options
	 * @return mixed
	 */
	protected function createModelByScheme($scheme, $options = [])
	{
		if (isset($scheme['type'])) {
			$class = self::ENTITY_NAMESPACE . mb_convert_case($scheme['type'], MB_CASE_TITLE);
		} else {
			$class = self::ENTITY_NAMESPACE . 'Tag';
		}

		$model = new $class(array_merge($scheme, $options));
		$model->parent = $this;

		return $model;
	}

	/**
	 * @throws \ErrorException
	 */
	protected function prototyping()
	{
		try {
			foreach ($this->_scheme as $k => $v) {
				$this->setModel($v);
			}
		} catch (\Exception $ex) {
			throw new \ErrorException(
				$ex->getMessage(),
				$ex->getCode()
			);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function mapArray(&$array)
	{
		foreach ($this->_model as $v) {
			$v->mapArray($array);
		}
	}

	/**
	 * Значение по пути в структуре объекта
	 * Ты конечно понимаешь что структура объекта, описывающего МТ документ, является деревом
	 * вот сюда нужно передать массив, указывающий на узлы дерева, по которым можно найти
	 * интересующее нас значение
	 * @param array $path
	 * @return Tag|Sequence
	 */
	public function getValueByPath($path)
	{
		$item = $this;
		foreach ($path as $v) {
			$item = $item->getNode($v);
			// @todo подумать над возможными ошибками для вероятных затруднений при отладке у непосвященных людей
			if (!is_object($item)) {
				return $item;
			}
		}

		return $item;
	}

	public function __clone()
	{
		parent::__clone();
		/**
		 * клонируем все объекты модели т.к. иначе сохраним только ссылки на исходный объект
		 */
		foreach ($this->_model as $k => $model) {
			$this->_model[$k] = clone $model;
			$this->_model[$k]->unsetValue();
			// потомки могут ссылаться на объект от которого клонированы мы
			$this->_model[$k]->parent = $this;
		}
	}

}
