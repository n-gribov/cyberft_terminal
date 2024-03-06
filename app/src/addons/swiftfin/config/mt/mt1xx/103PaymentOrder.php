<?php
namespace addons\swiftfin\config\mt1xx;
include_once(__DIR__ . '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use \Yii;

$accountsConfigPath = Yii::getAlias('@clientConfig/documents/mt/103PaymentOrder.php');
$accountNumbersList = file_exists($accountsConfigPath) ? require_once $accountsConfigPath : [];

/**
 * Только схема документа
 */
return [
	[
		'name'   => '20',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'Sender\'s reference',
		'mask'   => '6!n9n',
		'number' => '1',
		'scheme' => [
			[
				'name'  => 'date', //+
				'label' => 'Дата составления',
			],
			[
				'name'  => 'esNumber', //+
				'label' => 'Номер'
			],
		]
	],
	[
		'name'     => '23B',
		'status'   => Entity::STATUS_MANDATORY,
		'label'    => 'Код банковской операции',
		'mask'     => '4!c',
		'number'   => '3',
		'value'    => 'CRED',
		'constant' => true,
	],
	[
		'name'   => '26T',
		'status' => Entity::STATUS_OPTIONAL,
		'label'  => 'Код типа операции',
		'mask'   => '3!c',
		'number' => '5',
	],
	[
		'name'   => '32A',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'Value Date/Currency/Interbank Settled Amount',
		'mask'   => '6!nRUB15d',
		'number' => '6',
        'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32A',
		'scheme' => [
			[
				'name'  => 'date', // + в обработчике
				'label' => 'Дата валютирования'
			],
			[
				'name'  => 'sum', //+
				'label' => 'Сумма'
			],
		]
	],
	[
		'name'   => '50K',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'Плательщик',
		'number' => '9',
		'mask'   => "[/20!n]" . Entity::INLINE_BREAK . "[INN12x][.KPP9x]" . Entity::INLINE_BREAK . "3*35x",
		'scheme' => [
			[
				'name'   => 'accountNumber',//+
				'label'  => 'Расчетный счет',
				'strict' => !empty($accountNumbersList) ? $accountNumbersList : null,
			],
			[
				'name'  => 'inn', //+
				'label' => 'ИНН',
			],
			[
				'name'  => 'kpp',//+
				'label' => 'КПП',
			],
			[
				'name'  => 'nomination', //+ // name зарезервировано свойством тэга
				'label' => 'Наименование плательщика',
			],
		]
	],
	[
		'name'   => '52D',
		'status' => Entity::STATUS_OPTIONAL,
		'label'  => 'Банк плательщика',
		'number' => '11',
		'mask'   => "[/20!n]" . Entity::INLINE_BREAK . "/RU9!n",
		'scheme' => [
			[
				'name'  => 'accountNumber', // +
				'label' => 'Плательщик корсчет',
			],
			[
				'name'  => 'bik', // +
				'label' => 'БИК',
			],
		]
	],
	[
		'name'   => '57D',
		'status' => Entity::STATUS_OPTIONAL,
		'label'  => 'Банк получателя',
		'number' => '16',
		'mask'   => "[/20!n]" . Entity::INLINE_BREAK . "/RU9!n",
		'scheme' => [
			[
				'name'  => 'accountNumber', // +
				'label' => 'Получатель корсчет',
			],
			[
				'name'  => 'bik', // +
				'label' => 'БИК',
			],
		]
	],
	[
		'name'   => '59',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'Получатель',
		'number' => '17',
		'mask'   => "[/20!n]" . Entity::INLINE_BREAK . "[INN12x][.KPP9x]" . Entity::INLINE_BREAK . "3*35x",
		'scheme' => [
			[
				'name'  => 'accountNumber', // +
				'label' => 'Получатель расч. счет',
			],
			[
				'name'  => 'inn', // +
				'label' => 'ИНН',
			],
			[
				'name'  => 'kpp', // +
				'label' => 'КПП',
			],
			[
				'name'  => 'nomination', //+ // name зарезервировано свойством тэга
				'label' => 'Наименование получателя',
			],
		]
	],
	[
		'name'     => '71A',
		'status'   => Entity::STATUS_MANDATORY,
		'label'    => 'Детали расходов',
		'mask'     => '3!a',
		'number'   => '19',
		'constant' => true,
		'value'    => 'OUR',
	],
	[
		'name'   => '72',
		'status' => Entity::STATUS_OPTIONAL,
		'label'  => 'Sender to Receiver Information',
		'mask'   => '/RPP/3n.6!n.1!n[.4a].2!n[.6!n]' . Entity::INLINE_BREAK . "/DAS/[6!n.6!n][.6!n]",
		'number' => '21',
		'scheme' => [
			[
				'name'  => 'docNumber',
				'label' => 'Номер расчетного документа',
			],
			[
				'name'  => 'docDate',
				'label' => 'Дата выписки расчетного документа',
			],
			[
				'name'  => 'paymentPriority',
				'label' => 'Очередность платежа',
			],
			[
				'name'  => 'paymentType', //+
				'label' => 'Вид платежа',
			],
			[
				'name'  => 'operationType', // +
				'label' => 'Вид операции',
			],
			[
				'name'  => 'ed104',
				'label' => 'ED104',
			],
			[
				'name'  => 'withdraw', //+
				'label' => 'Списано',
			],
			[
				'name'  => 'enrolled', //+
				'label' => 'Поступило',
			],
			[
				'name'  => 'date',
				'label' => 'Дата',
			],
		]
	],
	[
		'name'   => '77B',
		'status' => Entity::STATUS_OPTIONAL,
		'label'  => 'Обязательная отчетность',
		'mask'   => '[/3c][/N10/2!a][/N4/20x]'
					. Entity::INLINE_BREAK . '[/N5/11x][/N6/2!a][/N7/10x]'
					. Entity::INLINE_BREAK . '[/N8/15x][/N9/10!x]',
		'number' => '22',
		'scheme' => [
			[
				'name'   => 'info',
				'label'  => 'Ведомственная информация',
				'strict' => ['DEP'],
			],
			[
				'name'  => 'paymentType', //+
				'label' => 'Показатель типа',
			],
			[
				'name'  => 'kbk', //+
				'label' => 'Показатель КБК',
			],
			[
				'name'  => 'okato', //+
				'label' => 'ОКАТО',
			],
			[
				'name'  => 'paymentReason', //+
				'label' => 'Показатель основания',
			],
			[
				'name'  => 'taxablePeriod', //+
				'label' => 'Показатель периода',
			],
			[
				'name'  => 'taxableNumber', //+
				'label' => 'Показатель номера',
			],
			[
				'name'  => 'taxableDocDate', //+
				'label' => 'Показатель даты',
			],
		]
	],
	[
		'name'   => '77T',
		'status' => Entity::STATUS_OPTIONAL,
		'label'  => 'Содержание конверта',
		'mask'   => '[/AER/215x]~'
					. Entity::INLINE_BREAK . '[/PEE/215x]~'
					. Entity::INLINE_BREAK . '/NZP/420x~[/SEN/10!n]~'
					. Entity::INLINE_BREAK . '[/INI/6n~.6!n~.10!x]~'
					. Entity::INLINE_BREAK . '[/SID/1!n]~'
					. Entity::INLINE_BREAK . '[/PID/25x]~'
					. Entity::INLINE_BREAK . '/SIGN/~/SGP/4*78x'
		,
		'number' => '23',
		'scheme' => [
			[
				'name'  => 'payerName',
				'label' => 'Наименование плательщика',
			],
			[
				'name'  => 'recipientName',
				'label' => 'Наименование получателя',
			],
			[
				'name'  => 'paymentAssignment',
				'label' => 'Назначение платежа',
			],
			[
				'name'  => 'uis',
				'label' => 'УИС составителя',
			],
			[
				'name'  => 'esNumber',
				'label' => 'Номер исходного ЭС',
			],
			[
				'name'  => 'esDate',
				'label' => 'Дата составления исходного ЭС',
			],
			[
				'name'  => 'usUniqueId',
				'label' => 'Уникальный идентификатор составителя исходного ЭС',
			],
			[
				'name'  => 'sessionNumber',
				'label' => 'Номер сеанса',
			],
			[
				'name'  => 'paymentUniqueId',
				'label' => 'Уникальный идентификатор платежа',
			],
			[
				'name'  => 'kaOption',
				'label' => 'Признак тега КА',
			],
			[
				'name'  => 'kaValue',
				'label' => 'Значение КА',
			],
		]
	],
];
