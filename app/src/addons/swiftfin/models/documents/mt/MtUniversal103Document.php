<?php

namespace addons\swiftfin\models\documents\mt;

/**
 * Class MtUniversal103Document
 * @property float $sum
 * @package addons\swiftfin\models\documents\mt
 */
class MtUniversal103Document extends MtUniversalDocument
{
	public $currency = 'RUB';
	public $aliases = [
		'currency' => ['32A', 'currency'],
		'sum'      => ['32A', 'sum'],
		'date'     => ['32A', 'date'],
	];

	public function rules()
    {
		return array_merge (
			[
				['sum', 'number', 'min' => 0, 'max' => 99999999999999],
			], parent::rules()
		);
	}

	/**
	 * @inheritdoc
	 */
	public function setValue($value)
    {
		$r = parent::setValue($value);
		$this->_model['32A']->setAttribute('date', $this->_model['20']->getAttribute('date'));
		// если что-то из замапленного на поле передано, то добиваем обязательные поля
		if ((string)$this->_model['72']) {
			$this->_model['72']->setAttribute('docNumber', $this->_model['20']->getAttribute('esNumber'));
			$this->_model['72']->setAttribute('docDate', $this->_model['20']->getAttribute('date'));
		}

		return $r;
	}

	public function attributeLabels()
    {
		return array_merge(
			[
				'sum' => $this->getNode('32A')->getAttributeLabel('sum')
			],
			parent::attributeLabels()
		);
	}

}