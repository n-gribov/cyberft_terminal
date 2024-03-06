<?php
namespace addons\edm\models\PaymentOrderForeignCurrency;

use common\components\TerminalId;
use addons\swiftfin\models\documents\mt\validator\MtExternalValidator;
use addons\swiftfin\models\documents\mt\MtUniversalDocument;
use addons\swiftfin\models\containers\swift\SwtContainer;
use addons\edm\models\DocumentTrait;

class PaymentOrderForeignCurrency extends MtUniversalDocument
{
	use DocumentTrait;

	const TYPE       = 'PaymentOrderForeignCurrency';
	const TYPE_LABEL = 'Распоряжение на перевод в иностранной валюте MT103';
	const LABEL      = 'Перевод в иностранной валюте';

	public function __construct($config = [])
    {
		/**
         *  @todo костылище
         */
		$mtConf = include(\Yii::getAlias('@addons/swiftfin/config/mt/mt1xx/103.php'));
		// корректируем до абсолютного пути
		$mtConf['view'] = \Yii::getAlias('@addons/swiftfin/views' . $mtConf['view']);
		unset($mtConf['class']);
		unset($mtConf['type']);
		unset($mtConf['label']);

		parent::__construct(array_merge($mtConf, $config));
	}

	public function init()
    {
		parent::init();
		// приращиваем счетчик и берем его значение
		$this->number = $this->counterIncrement()->getCounter();
	}

	public function rules()
    {
		return [
			['body', 'required'],
			['body', 'string', 'length' => [16, 10000]], // @todo добавить максимальную длину в настройки документа
			['body', MtExternalValidator::className(), 'type' => 103]
		];
	}

	/**
	 * @return SwtContainer
	 */
	public function getSwtContainer()
    {
		$recipient = TerminalId::extract($this->recipient);
		$recipient->terminalCode = $this->terminalCode;

		$swt = new SwtContainer();
		$swt->setRecipient($recipient);
		$swt->setSender($this->sender);
		$swt->terminalCode = $this->terminalCode;
		$swt->setContentType('103');
		$swt->setContent($this->getBody());

		return $swt;
	}

}
