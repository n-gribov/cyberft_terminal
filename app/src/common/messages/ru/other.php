<?php
/*
 * Other messages from many files. Placed here for speeding up translation.
 */
return [
	/*
	 * COMMON
	 */
	// modules/document/models/search/DocumentSearch.php:58:
    'Incorrect search criterion "{param}"' => 'Некорректный параметр поиска "{param}"',
	// modules/document/models/search/DocumentSearch.php:68:
	'Display documents' => 'Отображать документы',
	// modules/document/models/search/DocumentSearch.php:69:
	'Display system messages' => 'Показывать системные сообщения',
	// modules/document/models/search/DocumentSearch.php:78:
	'Outbox, not delivered to the addressee' => 'Исходящие, не доставленные адресату',
	// modules/document/models/search/DocumentSearch.php:88:
	'Inbox, with valid signatures' => 'Входящие, с валидными подписями',
	// modules/document/models/search/DocumentSearch.php:95:
	'Outbox, delivered to the addressee' => 'Исходящие, доставленные адресату',
	// modules/document/models/search/DocumentSearch.php:99:
	'Inbox, with invalid signatures' => 'Входящие, с невалидными подписями',
	// modules/document/models/search/DocumentSearch.php
	'Filters' => 'Фильтры',
	// modules/document/models/search/DocumentSearch.php
	'Inbox' => 'Входящие',

	// modules/document/models/Document.php:167:
	'The combination of UUID and Sender name has already been taken.' => 'Комбинация UUID и Отправителя уже используется.',

	// modules/document/models/containers/swift/SwaPackage.php:40:
	'File with SWA-document' => 'Файл с SWA-документом',

	// modules/document/controllers/DefaultController.php:79:
	'Inbox documents' => 'Входящие документы',
	// modules/document/controllers/DefaultController.php:94:
	'Outbox documents' => 'Исходящие документы',
	// modules/document/controllers/DefaultController.php:109:
	'Invalid documents' => 'Ошибочные документы',

	// modules/document/views/wizard/step2.php:60:
	'Form filling out error' => 'Ошибка заполнения формы',
	// modules/document/views/wizard/step2.php:100:
	'Switch editing modes by pressing F10' => 'Режимы редактирования переключаются нажатием клавиши F10',

	// modules/document/views/wizard/wizprint.php:59:60:61:62:
	'Your browser is out of date or is running in compatibility mode with the old version.' => 'Ваш браузер устарел или работает в режиме совместимости со старой версией.',
	'In that mode the web-application can run incorrectly.' => 'В данном режиме web-приложение может работать некорректно.',
 	'Update your browser or turn off the compatibility mode.' => 'Обновите Ваш браузер или отключите режим совместимости.',

	// modules/document/views/default/view.php:18:
	'Back' => 'Назад',

	// modules/document/views/default/_cuteSearch.php:26:
	// modules/document/views/default/_search.php:24:
	'Document registration date' => 'Дата регистрации документа',
	// modules/document/views/default/_cuteSearch.php:42:
	// modules/document/views/default/_search.php:38:
	'Search' => 'Искать',

	// modules/document/views/default/invalid.php:14:
	'Invalid outbox documents' => 'Ошибочные исходящие документы',
	// modules/document/views/default/invalid.php:21:
	'Refresh the table' => 'Обновить таблицу',
	// modules/document/views/default/invalid.php:26:
	// modules/document/views/default/_list.php:10:
	'No documents matched your query' => 'По выбранным условиям документов не найдено',
	'No enabled users found' => 'Не найдено пользователей, имеющих доступ',
    'No entries found' => 'Ничего не найдено',
	// modules/document/views/default/invalid.php:27:
	// modules/document/views/default/_list.php:11:
	'Shown from {begin} to {end} out of {totalCount} found' => 'Показано с {begin} по {end} из {totalCount} найденных',
	// modules/document/views/default/invalid.php:56:
	'Correct and send selected ones' => 'Исправить и отправить выбранные',

	// modules/document/views/default/_viewSwift.php:
	'Route' => 'Маршрут',
	'Contents' => 'Содержимое',
	'Text format' => 'Текстовый формат',

	// modules/document/views/default/index.php:8:
	'Document Register' => 'Журнал документов',

	// modules/document/views/default/index.php
	'Statements' => 'Выписки',

	// modules/document/views/default/_viewSwift.php:41:
	'MT-document view' => 'Вид MT-документа',
	// modules/document/views/default/_viewSwift.php:46:
	'Readable view' => 'Читаемый вид',
	// modules/document/views/default/_viewSwift.php:51:
	'Printable view' => 'Печатный вид',

	// modules/certManager/components/ssl/X509FileModel.php:256:
	'New file is not set' => 'Не задан файл сертификата',
	// modules/certManager/components/ssl/X509FileModel.php:259:
	'Cert file validation failed' => 'Не удалось проверить файл сертификата',
	// modules/certManager/components/ssl/X509FileModel.php:263:
	'Failed to save file {name} to {path}' => 'Не удалось сохранить файл {name} в {path}',

	// modules/certManager/views/cert/view.php:50:
	'Certificate file' => 'Файл сертификата',

	// modules/certManager/views/cert/update.php:7:
	'Edit: {id}' => 'Редактирование: {id}',

	/*
	 * BACKEND
	 */
	// views/site/error.php:19:
	'An error has occurred during communication with the server. If you find such behavior incorrect, please contact the support.' => 'При обращении к серверу произошла ошибка. Если Вы считаете что это некорректное поведение, пожалуйста свяжитесь со службой поддержки.',

	// views/site/index.php:7:;
	'CyberFT platform is a software and hardware solution that implements an informational protected channel for transfer of all common types of financial and commercial electronic document flow.' => 'Платформа CyberFT представляет собой программно-аппаратное решение, реализующее информационную защищенную магистраль передачи всех общепринятых типов финансового и коммерческого электронного документооборота.',
	// views/site/index.php:8:;
	'CyberFT platform has been developed by the Russian company <strong>CyberPlat</strong>, a leader on the market of electronic payments in Russia and CIS.' => 'Платформа CyberFT &nbsp; разработана российской компанией <strong>«КиберПлат»</strong>, лидером рынка электронных платежей России и СНГ.',
	// views/site/index.php:9:
	'CyberFT platform represents a convenient and totally secure solution that allows banks and their customers to' => 'Платформа CyberFT представляет собой удобное и абсолютно безопасное решение, позволяющее банкам и их клиентам',
	 // views/site/index.php:11:
	'Optimize financial transaction expenses' => 'Оптимизировать затраты на проведение финансовых транзакций',
	// views/site/index.php:12:
	'Execute transactions in conformity with Russian legislation' => 'Выполнять операции в полном соответствии с российским законодательством',
	// views/site/index.php:13:
	'Have a complete protection of transactions from foreign policy threats' => 'Полностью защитить транзакции от внешнеполитических угроз',
	// views/site/index.php:14:
	'Guarantee the customers integrity of information containing a trade secret' => 'Гарантировать клиентам сохранность информации, содержащей коммерческую тайну',

	// views/site/resetPassword.php:14:
	'Please enter your new password' => 'Пожалуйста, введите Ваш новый пароль',

	// views/site/hello.php:8:
	'Your certificate valid till' => 'Срок действия Вашего сертификата',
	// views/site/hello.php:10:
	'In 10 seconds you will be redirected to:' => 'В течении 10 секунд вы будете перемещены на:',

	// views/profile/dashboardAdmin.php:78:
	'Processes running on the server' => 'Процессы запущенные на сервере',
	// views/profile/dashboardAdmin.php:97:
	// views/profile/dashboardUser.php:122:
	'Chart of document statuses for the month' => 'График статусов документов за месяц',

	// views/profile/dashboardUser.php:60:
	'Diagram of document types' => 'Диаграмма типов документов',
	// views/profile/dashboardUser.php:70:
	'Diagram of document statuses' => 'Диаграмма статусов документов',

	// views/terminal/autobot.php:18:
	'No settings for controller were found' => 'Настройки контролёра не заданы',
	// views/terminal/autobot.php:19:
	'Generate the keys' => 'Генерировать ключи',

	// views/terminal/auto-processing.php:26:
	'The entered password does not match the key of the Controller' => 'Введенный пароль не соответствует ключу контролёра',
	// views/terminal/auto-processing.php:57:
	'Automatic processes running on the server' => 'Автоматические процессы, запущенные на сервере',
	// views/terminal/auto-processing.php:86:
	'No automatic processes were found' => 'Автоматические процессы не обнаружены',

	// views/terminal/autobot-generate.php:15:
	'Generating keys for the controller' => 'Генерация ключей контролёра',
	// views/terminal/autobot-generate.php:18:
	'Attention' => 'Внимание',
	// views/terminal/autobot-generate.php:19:
	'The created controller keys will be automatically installed into the cryptosystem of the Terminal. After installation Terminal will lose its connection to all participants of the system.' => 'Созданные ключи контролёра автоматически установятся в криптосистему Терминала. После установки, Терминал утратит связь со всеми участниками системы.',
	// views/terminal/autobot-generate.php:20:
	'To establish connection with CyberFT system you need to complete the e-signature validation form and register the new certificate in the processing.' => 'Для установления связи с системой CyberFT необходимо оформить акт о признании электронной подписи и зарегистрировать новый сертификат в процессинге.',
	// views/terminal/autobot-generate.php:21:
	'Continue' => 'Продолжить',
	// views/terminal/autobot-generate.php:27:
	'Private key password' => 'Пароль приватного ключа',
	// views/terminal/autobot-generate.php:31:
	'Confirm password' => 'Подтвердите пароль',
	// views/layouts/main.php:28:29:30:31:
	'Your browser is out of date or is running in compatibility mode with the old version. In that mode the web-application can run incorrectly.' => 'Ваш браузер устарел или работает в режиме совместимости со старой версией. В данном режиме web-приложение может работать некорректно.',
	'Update your browser' => 'Обновите Ваш браузер',
	'or' => 'или',
	'turn off the compatibility mode' => 'отключите режим совместимости',

    // /addons/fileact/views/default/index.php
    'FileAct Messages' => 'Документы FileAct',
    'View FileAct' => 'Просмотр FileAct',

    'FinZip Documents' => 'Документы FinZip',

    // /addons/finzip/views/wizard/index.php:3
    'Type and addressee selection' => 'Выбор получателя',

    // /addons/swiftfin/views/documents/index.php:14
    'SwiftFin Document Register' => 'Журнал документов SwiftFin',

    // /addons/swiftfin/views/documents/_search.php:49
    'Text search' => 'Текстовый поиск',

	// /addons/fileact/views/documents/sign.php
	'Certificate list' => 'Список сертификатов',

	// /addons/fileact/views/documents/sign.php
	'Sign' => 'Подписать',

	// common/modules/transport/controllers/DefaultController.php
	'ID is not specified' => 'Не указан ID',

	// common/modules/transport/views/default/view.php
	'Original message' => 'Исходное сообщение',
	'Result' => 'Результат',
	'Download' => 'Скачать',
    'File not yet processed' => 'Файл еще не обработан',
    'Services not found' => 'Сервисы не найдены',
    'Available services' => 'Доступные сервисы',
    'Service' => 'Сервис',

    // Журнал ошибок импорта
    'Document type' => 'Тип документа',
    'Create date' => 'Дата события',
    'Identity' => 'Идентификатор',
    'Filename' => 'Имя файла',
    'Error description' => 'Описание ошибки',
    'Document already exists' => 'Документ уже был создан ранее',
    'Invalid sender: {sender}' => 'Неизвестный отправитель: {sender}',
    'Invalid recipient: {recipient}' => 'Неизвестный получатель: {recipient}',
    'Could not create stored file: {type}' => 'Ошибка создания файла хранилища: {type}',
    'Error validating document' => 'Ошибка валидации документа',
    'Failed to get document type' => 'Ошибка получения типа документа',
    'Source document validation failed' => 'Ошибка валидации документа',
    'Source document validation against XSD failed' => 'Ошибка валидации документа по XSD-схеме',
    'Cannot find sender/receiver' => 'Отправитель/получатель не обнаружены',
    'Failed import file' => 'Ошибка импорта файла',
    'Cannot reserve document in DB' => 'Невозможно сохранить документ',
    'File doesn\'t exist or not readable' => 'Файл не найден или недоступен для чтения',
    'Bad swift file' => 'Некорректный swift-файл',
    'Empty swa package' => 'Пустой swa-архив',
    'Cannot write temp file' => 'Ошибка записи временного файла',
    'PaymentOrder model is not valid' => 'Ошибка валидации платежного поручения',
    'Failed to create payment order' => 'Ошибка создания платежного поручения',
    'Failed to create payment register: {error}' => 'Ошибка создания реестра платежных поручений {error}',
    'Account does not have bank terminal id' => 'Для счета не указан адрес терминала банка',
    'Identity error' => 'Ошибка идентификатора',
    'Error in payer field' => 'Ошибка в поле ПлательщикРасчСчет',
    'Error in payer bik field' => 'Ошибка в поле ПлательщикБик',
    'Failed to create {document} document' => 'Ошибка создания документа {document}',
    'Invalid 1c file: {errors}' => 'Некорректный 1с-файл: {errors}',
    'Reference {id} is already used in an another operation' => 'Референс {id} уже используется в другой операции',
    'IBank file processing errors: {errors}' => 'Ошибка обработки файла IBank: {errors}',
    'Failed to create camt.053 file from IBank: {errors}' => 'Ошибка создания camt.053 из файла IBank: {errors}',
    'Processing FileAct failed: {xml}. Can not find bin file {bin}' => 'Ошибка обработки FileACT: {xml}. Не найден указанный BIN файл: {bin}',
    'Unknown sender {sender}' => 'Неизвестный отправитель {sender}',
    'Unknown recipient {recipient}' => 'Неизвестный получатель {recipient}',
    'The archive do not contains any xml files' => 'В архиве отсутствует ключевой xml-файл',
    'Attachments are not allowed for this document type: {type}' => 'Вложения не поддерживаются для документов типа {type}',
    'Attachment information does not match number of files' => 'Информация о количестве вложений в xml не совпадает с фактическим количеством файлов',
    'Failed to find attachment info: {filename}' => 'В ключевом xml-файле не найдена информация о вложении: {filename}',
    'File digest value is invalid: {filename}' => 'Невалидный digest для вложения: {filename}',
    'The archive contains more than one xml file' => 'Архив содержит более одного xml-документа',
    'The archive does not contain any xml files' => 'Архив не содержит ни одного xml-документа',

];