<?php
namespace addons\swiftfin\models\documents\mt\mtUniversal;

use \addons\swiftfin\models\documents\mt\widgets\Choice as Widget;

/**
 * Class Choice
 *
 * @todo    несколько костыльная сущность получилась, отрефакторить и адапатировать под тэги с множеством значений
 * @package addons\swiftfin\models\documents\mt\mtUniversal
 */
class Choice extends Sequence
{
	const NO_LETTER_NAME = 'NoLetter';

	public $choice;
	public $number; // для удобства навигации по конфигу и документации
    public $wrapperClass;

	/**
	 * @inheritdoc
	 */
	public function init()
    {
		parent::init();
		//$this->choice = key($this->_model);
	}

	/**
	 * @inheritdoc
	 */
	public function setValue($value)
    {
		if (is_array($value)) {
			parent::setValue($value);
			if (isset($value['choice'])) {
				$this->choice = $value['choice'];
			}
		} else if (isset($this->choice)) {
			if ($this->_model[$this->choice] instanceof Tag) {
				$this->_model[$this->choice]->setValue($value);
			} else if ($this->_model[$this->choice] instanceof Sequence) {
				/** @var Sequence $first */
				$first = current($this->_model[$this->choice]->getModel());
				$first->setValue($value);
			}
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getValue($translitDecode = false)
    {
		if (!isset($this->choice) || !isset($this->_model[$this->choice])) {
			return null;
		}

		if ($this->_model[$this->choice] instanceof Tag) {
			return $this->_model[$this->choice]->getValue($translitDecode);
		} else if ($this->_model[$this->choice] instanceof Sequence) {
			$substr = [];
			foreach ($this->_model[$this->choice]->getValue() as $v) {
				$substr[] = $v->getValue($translitDecode);
			}

			return implode('', $substr);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getChoiceLabels()
    {
		$keys = array_keys($this->_model);

		return array_combine($keys, $keys);
	}

	/**
	 * @return string
	 */
	public function getNameBasis()
    {
		return substr($this->_name, 0, 2);
	}

	/**
	 * @inheritdoc
	 */
	public function toHtmlForm($form = null)
    {
		return Widget::widget([
			'model' => $this,
			'form'  => $form
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function mapArray(&$array)
    {
		$current = current($array);
		// т.к. есть фишка с no letter именами добавляем в паттерн валидным пустое значение
		$pattern = '/^' . $this->getNameBasis() . '(?P<choice>' . implode('|', array_keys($this->_model)) . '|)$/';
		if (preg_match($pattern, $current['name'], $found)) {
			if (!$found['choice']) {
				$this->choice = self::NO_LETTER_NAME;
			} else {
				$this->choice = $found['choice'];
			}
			$this->setValue($current['value']);
			next($array);

			return true;
		}

		return false;
	}


	/**
	 * @inheritdoc
	 */
	public function __toString()
    {
		$str = $this->getValue();
		if (strlen($str) > 0) {
			if ($this->choice === self::NO_LETTER_NAME) {
				$letter = '';
			} else {
				$letter = $this->choice;
			}

			return ':' . $this->getNameBasis() . $letter . ':' . $str;
		}

		return '';
	}

	/**
	 * @inheritdoc
	 */
	public function toReadable($translitDecode = false)
    {
		$value = $this->getReadableValue($translitDecode);

		if (strlen($value) > 0) {
			if ($this->choice === self::NO_LETTER_NAME) {
				$letter = '';
			} else {
				$letter = $this->choice;
			}
			$linePad = "\t\t";//str_repeat(' ', $this->printableNamePadSize);

			return ' ' . $this->getNameBasis() . $letter . "\t:\t" . $this->label
                    . self::LINE_BREAK
                    . $linePad . preg_replace("/([\r\n]{1,2})/m", "$1$linePad", $value)
                    . self::LINE_BREAK;
		}

		return '';
	}

	public function getReadableValue($translitDecode = false)
    {
		if (!isset($this->choice) || !isset($this->_model[$this->choice])) {
			return null;
		}

		if ($this->_model[$this->choice] instanceof Tag) {
			return $this->_model[$this->choice]->getReadableValue($translitDecode);
		} else if ($this->_model[$this->choice] instanceof Sequence) {
			$substr = [];
			foreach ($this->_model[$this->choice]->getValue() as $v) {
				$substr[] = $v->getValue($translitDecode);
			}

			return 'seq.' . implode('', $substr);
		}
	}

	public function toPrintable($translitDecode = false)
    {
		return $this->toReadable($translitDecode);
	}

	/**
	 * @inheritdoc
	 */
	public function __clone()
    {
		parent::__clone();
		$this->choice = key($this->_model);
	}

	/**
	 * @inheritdoc
	 */
	protected function prototyping()
    {
		parent::prototyping();

		//Заккоментируем т.к. в форме нам нужны пустые начальные значения
		/*if (!$this->choice) {
			$this->choice = key($this->_model);
		}*/
	}

	protected function setModel($scheme, $options = [])
    {
		$model = $this->createModelByScheme($scheme, $options);
		if (
			!isset($scheme['name']) || !$scheme['name']
			|| !isset($model->name) || !$model->name
		) {
			$model->name = self::NO_LETTER_NAME;
			if (!$model->label) {
				$model->label = \Yii::t('doc/mt', 'No letter option');
			}
		}

		if (!$model->label) {
			$model->label = \Yii::t('doc/mt', 'Option {option}', ['option' => $model->name]);
		}

		$model->dataType = 'choice';
		$this->_model[$model->name] = $model;

		return $this;
	}

}