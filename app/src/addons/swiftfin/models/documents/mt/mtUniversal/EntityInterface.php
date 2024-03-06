<?php
namespace addons\swiftfin\models\documents\mt\MtUniversal;

/**
 * @property mixed $value
 * @package addons\swiftfin\models\documents\mt\MtUniversal
 */
interface EntityInterface
{
	public function toHtmlForm($form =null);

	public function __toString();

	/**
	 * Set real full entity value
	 * @param $value
	 */
	public function setValue($value);

	/**
	 * Get real full entity value
	 * @return mixed
	 */
	public function getValue();

	/**
	 * Пытаемся замапить очередной элемент массива на себя
	 * в случае если по текущему указателю в массиве элемент мы на себя мэпим
	 * возвращаем true и по необходимости смещаем указатель на следующий элемент
	 * (по сути смещать курсор должен делать только тот объект который раельно применил к себе пришедшие данные)
	 * иначе просто возвращаем false
	 *
	 * @param array $array
	 * @return boolean
	 */
	public function mapArray(&$array);

	/**
	 * Получение дочернего вложенного объекта по его имени
	 * @param $name
	 * @return mixed
	 */
	public function getNode($name);
}