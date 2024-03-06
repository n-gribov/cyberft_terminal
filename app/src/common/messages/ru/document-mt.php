<?php
return [
	'Generated file "{path}"' => 'Создан файл "{path}"',
	'Type {type} is not supported'	=> 'Тип {type} не поддерживается',
	
	'Create document' => 'Создать документ',
	'Edit document' => 'Редактировать документ',
	'Send document' => 'Отправить документ',
	'Save as template' => 'Сохранить шаблон',
	
	'Document has errors' => 'Документ содержит ошибки',
	
	//Форма создания	
	'Form mode'	=> 'Режим формы',
	'Raw text mode'	=> 'Текстовый режим',
	'Check document'	=> 'Проверить документ',
	'Value should be like "{pattern}"'	=> 'Значение должно быть вида "{pattern}"',
	
	// Wizard Step 1 prompts
	'Select recipient' => 'Выберите получателя',
	'Select document type' => 'Выберите содержимое документа',
	// Wizard Step 2 prompts
	// 'Select currency' => 'Выберите валюту',

	'Document body'				=> 'Тело документа',

	// Поля  MT103
	'Document data'				=> 'Данные документа',
	'Sender\'s reference'		=> 'Референс отправителя',
	'Bank operation code'		=> 'Код банковской операции',
	'Transaction type code'		=> 'Код типа операции',
	'Value Date / Currency / Interbank Settled Amount'		=> 'Дата\Валюта\Сумма',
	'Time indication' => 'Указание времени',
	'Instruction code' => 'Код инструкций',
	'Envelope Contents' => 'Содержание конверта',
	'Currency / Instructed Amount' => 'Валюта/Сумма платежного поручения',
	'Exchange Rate' => 'Курс конвертации',
	'Sending Institution' => 'Организация-Отправитель',
	'Third Reimbursement Institution' => 'Третий банк возмещения',
	'Sender\'s Charges' => 'Расходы Отправителя',
	'Receiver\'s Charges' => 'Расходы Получателя',

	// 50a
	'Ordering Customer'			=> 'Плательщик',
	// Наименования всех опций поля 50a
	'Account'					=> 'Счет',
	'BIC/BEI'					=> 'Идентификационный код',
	'Party Identifier'			=> 'Идентификация стороны',
	'Name & Address'			=> 'Наименование и Адрес',
	// Составные наименования всех опций поля 50a
	'Party Identifier / Name & Address'	=> 'Идентификация стороны\Наименование и Адрес',
	'Account / Name & Address'	=> 'Счет\Наименование и Адрес',
	'Account / BIC/BEI'			=> 'Счет\Идентификационный код',
	
	/* Replaced by Ordering Institution
	'Originator\'s Bank'		=> 'Банк Плательщика',
	 */
	// 52a
	'Ordering Institution'		=> 'Банк Плательщика',
	'Party Identifier / BIC' => 'Идентификация стороны\Идентификационный код',
	// 56a
	'Intermediary Institution'	=> 'Посредник',
	// 59a
	'Beneficiary Customer'		=> 'Бенефициар',
	
	'Remittance Information'		=> 'Информация о платеже',
	'Details of Charges'		=> 'Детали расходов',
	'Sender to Receiver Information'		=> 'Информация Отправителя Получателю',
	'Regulatory Reporting'		=> 'Обязательная отчетность',
	
	//Поля MT202
	'Transaction Reference Number'	=> 'Референс операции', 
	'Related Reference'	=> 'Связанный референс', 
	'Value Date/Currency/Interbank Settled Amount'	=> 'Дата валютирования, код валюты, сумма',
	'Sender\'s Correspondent'	=> 'Корреспондент Отправителя', 
	'Receiver\'s Correspondent'	=> 'Корреспондент Получателя',
    // 57a
	'Account With Institution'	=> 'Банк Бенефициара', 
	'Party Identifier / Location' => 'Идентификация стороны\Местонахождение',
	'Location'					=> 'Местонахождение',
	'Beneficiary Institution'	=> 'Бенефициар', 

	// Поля MT999
	'Narrative' => 'Свободный текст',


	// Validators
	'The value "{value}" does not match the character group "{group}". Look official base documentation' =>
		'Значение "{value}" не соответствует символьной группе "{group}". Смотрите официальную документацию',
	'The value "{value}" does not match the mask "{mask}". Look official base documentation' =>
		'Значение "{value}" не соответствует маске "{mask}". Смотрите официальную документацию',

	//Валидация из перлового компонента
	'Missing required field'	=> 'Пропущено обязательное поле',
	'The {attribute} value "{value}" does not match the mask "{mask}". Look official base documentation' => '{attribute} значение "{value}" не соответствует маске "{mask}". Для справки смотрите официальную документацию.',
    'Value "{value}" does not match the mask "{mask}"' => 'Значение "{value}" не соответствует маске "{mask}"',

    'Date' => 'Дата',
    'Currency' => 'Валюта',
    'Sum' => 'Сумма',
];