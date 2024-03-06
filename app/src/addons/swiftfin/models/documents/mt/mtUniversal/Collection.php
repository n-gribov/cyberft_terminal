<?php
namespace addons\swiftfin\models\documents\mt\mtUniversal;

use \addons\swiftfin\models\documents\mt\widgets\Collection as Widget;

class Collection extends Sequence
{
	/**
	 * @param null $form
	 * @return string
	 */
	public function toHtmlForm($form = null)
    {
		return Widget::widget([
			'model' => $this,
			'form'  => $form
		]);
	}

	/**
	 * @param array $value
	 * @return $this
	 * @throws \yii\base\Exception
	 */
	public function setValue($value) {
		$c = count($value);
		if ($c > 1) {
			// расширяем прототип для наполнения структуры данными
			for ($i = 1; $i < $c; $i++) {
				$this->increase();
			}
		}

        return parent::setValue($value);
	}

	/**
	 * @inheritdoc
	 */
	public function mapArray(& $array)
    {
		/**
		 * параллельно проходимся по последовательности
		 * массиву имеющихся значений
		 */
		$totalFound = 0;
		$i = 0;
		do {
			$found = false;
			/**
			 * сверяем текущую последовательность пока она не окончится
			 * @var Sequence $sequence
			 */
			$sequence = current($this->_model);
			foreach ($sequence->getModel() as $item) {
				if ($item->mapArray($array)) {
					$found = true;
					$totalFound++;
				}
			}
			/**
			 * Раз что-то нашли, то добавляем еще один элемент коллекции и сверяемся с ним
			 */
			if ($found) {
				$this->increase();
				next($this->_model);
			}
			$i++;
			/**
			 * до тех пор пока что-то удается найти но не более 50 раз, на случай ошибок зацикливания
			 */
		} while ($found === true || $i > 50);
		reset($this->_model);

		// удаляем лишний элемент
		if ($i > 1) {
			unset($this->_model[$i]);
		}

		return (bool)$totalFound;
	}

	public function __clone()
    {
		if (($c = count($this->_model)) && $c > 1) {
			for ($i = 1; $i < $c; $i++) {
				unset($this->_model[$i]);
			}
		}

		parent::__clone();
	}
	
	public function count()
    {
		return count($this->_model);
	}

	protected function increase()
    {
		$k                = count($this->_model);
		$this->_model[$k] = clone $this->_model[0];
		$this->_model[$k]->setName((string)$k);
	}

	/**
	 * @throws \ErrorException
	 */
	protected function prototyping()
    {
    	$config           = [
            'disableLabel'     => true,
            'disableContainer' => true,
            'scheme'           => $this->_scheme,
            'name'             => '0'
        ];

		try {
			$sequence         = new Sequence($config);
			$sequence->parent = $this;
			$this->_model     = [$sequence];
		} catch (\Exception $ex) {
			throw new \ErrorException(
				$ex->getMessage() . "\nLook config in reverse order: name = "
				. (isset($config['name']) ? $config['name'] : 'undefined'),
				$ex->getCode()
			);
		}
	}

}
