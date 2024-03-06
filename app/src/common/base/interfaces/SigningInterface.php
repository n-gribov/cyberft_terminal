<?php
namespace common\base\interfaces;

/**
 * Для реализации блоков приложения, которые занимаются подписанием документов
 */
interface SigningInterface
{
	public function isSignatureRequired($origin, $terminalId = null);
	public function getSignaturesNumber($terminalId = null);
}
