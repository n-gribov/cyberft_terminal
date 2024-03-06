<?php
namespace addons\swiftfin\models\documents\mt;

use common\components\TerminalId;

class MtUniversal300 extends MtUniversalDocument
{
    protected function prototyping()
    {
		parent::prototyping();

        if ($this->hasOwner()) {
			// скрываем поле, т.к. будем вычислять его значение динамически
			$this->getValueByPath(['A', '22C'])->invisible = true;
		}
	}

	/**
	 * @return $this
	 */
	protected function afterLoad()
    {
        if (!$this->hasOwner()) {
			return $this;
		}

		$sender = TerminalId::extract($this->owner->getSender());
		$recipient = TerminalId::extract($this->owner->getRecipient());
		$recipient->terminalCode = $this->owner->getTerminalCode();

		// считаем Общий референс
		// берем аттрибуты
		$reference = $this->getValueByPath(['A', '22C']);
		$reference->setAttribute('bank1Code', $sender->participantCode);
		$reference->setAttribute('bank1Place', $sender->sevenSymbol . $sender->delimiter);
		$reference->setAttribute('bank2Code', $recipient->participantCode);
		$reference->setAttribute('bank2Place', $recipient->sevenSymbol . $recipient->delimiter);

		$refCode = (string)(float)str_replace(',', '.', $this->getValueByPath(['B', '36'])->value);
		$refCode = substr(str_replace('.', '', $refCode), -4);
		$refCode = str_pad($refCode, 4, '0', STR_PAD_LEFT);
		$reference->setAttribute('referenceCode', $refCode);

		$reference->setAttribute('bank2Place', $recipient->sevenSymbol . $recipient->delimiter);

		return $this;
	}
}