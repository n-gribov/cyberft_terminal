<?php
return [
    'Yes'   => 'Да',
    'No'   => 'Нет',
	'and' => 'и',
	'or' => 'или',

	'Upload new file' => 'Загрузить новый файл',
	'View' 			=> 'Просмотр',
	'Documentation'	=> 'Документация',
	'Documentation (beta)'	=> 'Документация (beta)',
	'Go' 			=> 'Перейти',
	'Example'      => 'Пример',
	'Documents'    => 'Документы',
    'Documents with errors' => 'Ошибочные документы',
	'Users'        => 'Пользователи',
	'Setup'        => 'Настройка',
	'Settings'       => 'Настройки',
	'Messages'     => 'Сообщения',
    'About'        => 'О системе',
    'Participant "{id}"'  => 'Участник "{id}"',

	'Create document' => 'Создать документ',
	'Edit document' => 'Редактировать документ',
	'Edit' => 'Редактировать',
	'View document'	=> 'Просмотр документа',
	'Error: {message}' => 'Ошибка: {message}',

    'View FinZip' => 'Просмотр FinZip',

    'Upload files' => 'Загрузка файлов',

	'Return' => 'Вернуться',
	'Back' => 'Назад',
	'Next' => 'Далее',
	'Confirm' => 'Подтвердить',
	'Send' => 'Отправить',
	'Do not send' => 'Не отправлять',
	'Update' => 'Редактировать',
	'Create' => 'Создать',
	'Delete' => 'Удалить',
    'Restore' => 'Восстановить',
	'Search' => 'Искать',
	'Reset' => 'Сбросить',
	'Save' => 'Сохранить',
    'Save and send' => 'Сохранить и отправить',
    'Add' => 'Добавить',
    'Download' => 'Загрузить',
	'Upload' => 'Загрузить',
	'Browse' => 'Обзор',
	'Replace' => 'Заменить',
	'Load' => 'Загрузить',
	'Cancel' => 'Отмена',
    'Continue' => 'Продолжить',
    'Required signatures' => 'Необходимо подписей',
	'Proceed with signing' => 'Перейти к подписанию',
	'Sign with private key' => 'Подписать личным ключом',
	'Finish' => 'Завершить',
	'File' => 'Файл',
    'Files' => 'Файлы',
    'Add Files' => 'Добавить файлы',

	'Deleted'	=> 'Удален',
	'Active'	=> 'Активен',
	'Locked'	=> 'Заблокирован',

	'Administrator'	=> 'Главный администратор',
    'Additional administrator'	=> 'Администратор',
	'User' => 'Пользователь',
	'Signer' => 'Подписант',
	'Controller' => 'Проверяющий',
	'Operator' => 'Оператор',

	'Value' => 'Значение',
    'Value must be in range {min}-{max}' => 'Значение должно быть от {min} до {max}',
	'Update {modelClass}: ' => 'Редактировать {modelClass}: ',
	'Additional settings for {modelClass}: ' => 'Дополнительные настройки {modelClass}: ',

	'Action is forbidden'	=> 'Действие запрещено',

	'File path is empty' => 'Путь к файлу не задан',
	'Cannot read directory {path}'	=> 'Невозможно чтение директории {path}',
	'Cannot write into directory {path}'	=> 'Невозможна запись в директорию {path}',
	'Cannot have full access to directory {path}'	=> 'Нет полного доступа в директорию {path}',
	'Choose file for upload' => 'Выберите файл для загрузки',
	'Sync with {syncSrc}' => 'Синхронизировать с {syncSrc}',
	'Error getting document for edit' => 'Ошибка получения документа для редактирования',
	'Save document error' => 'Ошибка сохранения документа',
    'Create document error' => 'Ошибка создания документа',
    'Show deleted entries' => 'Показать удаленные записи',
    'Selected' => 'Выбрано',
    'Select all' => 'Отметить все',
    'Are you sure you want to delete selected entries?' => 'Вы уверены, что хотите удалить отмеченные записи?',
    'Delete selected' => 'Удалить выделенные',

	/*
	 * Login
	 */
	'Login' => 'Вход в систему',
	'Login with password' => 'Вход в систему с паролем',
	'Login with key' => 'Вход в систему с помощью ключа',
    'Login by key' => 'Войти по ключу',
    'Log in with password' => 'Войти с паролем',
	'Email' => 'Email', // Поле: адрес электронной почты
	'Password' => 'Пароль', // Поле: пароль
	'Old password' => 'Старый пароль',
	'New password' => 'Новый пароль',
	'Confirm password' => 'Подтверждение пароля',
	'Passcode' => 'Пароль',
	'Remember me' => 'Запомнить', // Чекбокс: Запомнить меня
	'Log in' => 'Войти', // Кнопка входа
	'Reset password' => 'Сброс пароля',
	'Select the authentication certificate' => 'Выберите сертификат для аутентификации',
	'Sign impossible. To enter you need to use thin client CyberFT' => 'Вход невозможен. Для входа вам необходимо использовать тонкий клиент CyberFT',
	'Sorry, login problem' => 'Извините, проблемы со входом',
	'Incorrect current password' => 'Неверный текущий пароль',
	'New password was saved' => 'Новый пароль сохранён',
    'Set new password failed' => 'Не удалось установить новый пароль',
    'Authorization has failed. After a number of unsuccessful authorization attempts account can be blocked. If you cannon remember your password please try to <a href="{resetUrl}">reset it</a>.'
        => 'Ошибка авторизации. Обращаем ваше внимание на то, что после нескольких неудачных попыток входа учетная запись может быть заблокирована. Если вы не можете вспомнить пароль, попробуйте <a href="{resetUrl}">восстановить его</a>.',
    'Recover password' => 'Восстановить пароль',
    'Password recovery' => 'Восстановление пароля',
    'Check your email for further instructions.' => 'Письмо с инструкцией отправлено на ваш адрес электронной почты.',
    'Sorry, we are unable to reset password for email provided.' => 'К сожалению не удалось отправить письмо на ваш адрес электронной почты.',
    'You were inactive for a while. For security reasons your session was closed. If you want to continue, please log in again.' => 'Некоторое время вы оставались неактивны. В целях безопасности соединение с сервером было прервано. Если вы хотите продолжить работу, выполните повторный вход.',
    'Your account is blocked or not activated! Contact your administrator!' => 'Ваша учетная запись заблокирована или не активирована! Обратитесь к администратору!',

	/*
	 * Document actions
	 */
	'Actions' => 'Действия',
	'Other actions' => 'Другие действия',
	'Print' => 'Печать',
	'Print document' => 'Напечатать документ',
	'Save as {format}' => 'Сохранить в {format}',
	'Export as {format}' => 'Экспортировать в {format}',
	'Close' => 'Закрыть',
    'Received by CyberFT' => 'Поступило по CyberFT',

	/**
	 * Document Controller
	 */
	'Error in document #{id}: {message} ' => 'Ошибка документа #{id}: {message} ',
	'Fixed {count} documents'	=> 'Исправлено документов: {count} ',
	'Document cannot be fixed'	=> 'Документ не может быть исправлен',
	'Document fixed'	=> 'Документ исправлен',
	'Document is signed'	=> 'Документ подписан',
    'Show deletable documents only' => 'Отобразить документы доступные для удаления',

	/**
	 * Terminal
	 */
	'Download file'	=> 'Скачать файл',
	'Certificate content'	=> 'Содержимое сертификата',
	'Terminal configuration saved' => 'Настройки терминала сохранены',
	'Document print configuration saved' => 'Настройки автопечати документов сохранены',
	'Signatories configuration saved' => 'Настройки подписантов сохранены',
    'Error! The configuration not saved!' => 'Ошибка! Настройки не сохранены!',
    'Configuration saved' => 'Настройки сохранены',
	'Auto processing started' => 'Автопроцессинг запущен',
	'Auto processing stopped' => 'Автопроцессинг остановлен',
	'Auto processing recovered' => 'Выполнено восстановление начального состояния',

	'Auto processing is running {time}' => 'Автоматические процессы запущены {time}',
	'Auto processing is not running' => 'Автопроцессинг не запущен',

	'Private key invalid password' => 'Неправильный пароль приватного ключа',

	'Controller configuration updated' => 'Настройки контролёра обновлены',

	'User keys' => 'Ключи пользователя',
	'Generate keys' => 'Генерировать ключи',
	'Generate new user keys' => 'Генерация новых ключей пользователя',
	'Fingerprint' => 'Отпечаток',
	'Certificate fingerprint' => 'Отпечаток сертификата',
	'Certificate file path' => 'Путь к файлу сертификата',
    'Beneficiaries' => 'Используется для получателей',
	'Public key' => 'Публичный ключ',
	'Private key' => 'Приватный ключ',
	'Certificate' => 'Сертификат',
	'Public key file path' => 'Путь к файлу публичного ключа',
	'Private key file path' => 'Путь к файлу приватного ключа',
	'Private key password' => 'Пароль к приватному ключу',
	'Is valid password' => 'Пароль верный',
	'Configuration last update time' => 'Время последнего обновления настроек',
	'Remove user key' => 'Удалить ключи пользователя',
	'Remove key' => 'Сбросить ключи контролёра',
	'Controller key reset' => 'Ключ контролёра сброшен',
	'Unable to reset key' => 'Невозможно сбросить ключ контролёра, т.к. он находится в некорректном состоянии',
	'Unable to remove file' => 'Не могу удалить файл',
	'Unable to recovery: invalid private key password' => 'Восстановление невозможно: неправильный пароль приватного ключа',
	'Are you sure you want to generate keys?\nPreviously created keys will be overwritten.' => 'Вы уверены, что хотите сгенерировать новые ключи?\nЛюбые предыдущие ключи будут перезаписаны.',
	'User keys successfully created' => 'Ключи пользователя успешно созданы',
	'There was an error creating keys' => 'Произошла ошибка при создании ключей пользователя',
    'updated at {timestamp}'    => 'обновлено {timestamp}',

	/**
	 * Console
	 */
	'Terminal status' => 'Статус терминала',
	'Autoprocesses list' => 'Список Автопроцесов',
	'No Autoprocesses found' => 'Автопроцессы не обнаружены',
	'Recovering' => 'Восстановление состояния терминала',

	/**
	 * Interactive tours
	 */
	'This request must not be called directly' => 'Этот запрос нельзя исполнить',

	/*
	 * headerPanel
	 */
	'Terminal' => 'Терминал',
	'Log out' => 'Выйти',

	/*
	 * Language switcher
	 */
	'Russian' => 'Русский',
    'English' => 'English',

	'ru' => 'Русский',
    'en' => 'English',

	/*
	 * Validators
	 */
	'Path {value} not found' => 'Путь {value} не найден',
	'No access to the directory {value}' => 'Нет доступа к директории {value}',
	'The {attribute} must contain only digits' => '{attribute} должен содержать только цифры',
	'Search for a {label}' => 'Поиск по {label}',
	'Broken file' => 'Некорректный файл',

	/*
	 * User
	 */
	'Name'		 => 'Имя',
	'Role'		 => 'Роль',
	'Role settings' => 'Настройки роли',
	'Status'	 => 'Статус',
	'Created'	 => 'Создан',
	'Changed'	 => 'Изменен',
	'Swift role' => 'Swift-роль',
	'Preliminary Authorizer' => 'Предварительный авторизатор',
	'Authorizer' => 'Авторизатор',
	'Authorize'  => 'Авторизовать',
	'None'		 => 'Нет',
	'User access' => 'Доступ пользователей',
    'Authorized' => 'Авторизовано',
    'Preauthorized' => 'Предварительно авторизовано',

	/*
	 * Passwords and signup
	 */
	'User name is already in use.' => 'Имя пользователя уже используется.',
	'This email is already in use.' => 'Данный email уже используется.',
	'User name' => 'Имя пользователя',
	'Incorrect user name or password.' => 'Неверное имя пользователя или пароль.',
	'No users with indicated email were found' => 'Пользователь с указанным email\'ом не найден.',
	'Password changed for {name}' => 'Пароль изменен для {name}',
	'Password reset token cannot be blank.' => 'Маркер сброса пароля не может быть пустым.',
	'Wrong password reset token.' => 'Неверный маркер сброса пароля.',
	'Password reset request' => 'Запрос на восстановление пароля',
    'You can login only with key.' => 'Вы можете войти только по ключу',
    'You can login only with password.' => 'Вы можете войти только по паролю',

	/*
	 * FormSigner widget
	 */
	'Your browser does not support any of the existing ways of e-signature implementation' => 'Ваш браузер не поддерживает ни один из имеющихся механизмов электронной подписи.',
	'Signature driver has encountered an error' => 'Ошибка работы драйвера подписи',
	'Please, select the certificate to sign the action' => 'Необходимо выбрать сертификат для подписи действия',

	/*
	 * Hello
	 */
	'Welcome' => 'Добро пожаловать',

	/*
	 * User menu
	 */
	'Logged in as' => 'Вы вошли как',
	'Your signature number' => 'Ваша очередность подписания',
	'Your role' => 'Ваша роль',

	'Wrong file extension. Allowed files: *.swt, *.xml' => 'Неверное расширение файла. Разрешенные файлы: *.swt, *.xml',

	/*
	 * Version and info
	 */
    'Copyright 1997-{year} CyberPlat' => '&copy; 1997-{year} ООО &laquo;КИБЕРПЛАТ&raquo;',
	'{appname}, version {version} {tag}, primary terminal {terminalId}' => '{appname}, версия {version} {tag}, основной терминал {terminalId}',

	/**
	 * Words
	 */
	'City' => 'Город',
	'Currency' => 'Валюта',
	'Type' => 'Тип',
	'File deleted' => 'Файл удален',
	'File name' => 'Имя файла',
	'File path' => 'Путь',
	'All' => 'Все',

	/**
	 * Download
	 */
	'File status' => 'Статус файла',
	'File is being processed' => 'Файл в обработке',
	'File processing error' => 'Ошибка обработки файла',

	/**
	 * Signing tabs
	 */
	'Signatures' => 'Подписи',
	'Automatic signing' => 'Автоматическое подписание',
	'Manual signing' => 'Ручное подписание',
	'Status of signature' => 'Статус подписания',
	'Signed' => 'Подписан',
	'Not signed' => 'Не подписан',
	'Signatures type' => 'Тип подписи',
	'Automatic' => 'Автоматическая',
	'Manual' => 'Ручная',

	/**
	 * Report
	 */
	'Reports' => 'Отчёты',
	'Signatories' => 'Подписанты',
	'The method of signing' => 'Способ подписания',
	'Auto signing' => 'Автоподписание',
	'Key type' => 'Тип ключа',
	'Module' => 'Модуль',
	'Default view of Reports section' => 'Вид по умолчанию для раздела "Отчёты"',

    /**
     * Command
     */
    'ID' => 'ID',
    'Entity' => 'Сущность',
    'Entity ID' => 'ID сущности',
    'Count of accepts' => 'Количество подтверждений',
    'User ID' => 'ID пользователя',
    'Arguments' => 'Параметры',
    'Result of command' => 'Результат команды',
    'Date of create' => 'Дата создания',
    'Date of update' => 'Дата обновления',
    'Command ID' => 'ID команды',
    'Data' => 'Данные',
    'Command code' => 'Код команды',
    'Accepted' => 'Принято',
    'Rejected' => 'Отклонено',
    'Accept result' => 'Результат',
    'Approve date' => 'Дата утверждения',
    'No approve matched your query' => 'По выбранным условиям утверждений не найдено',
    'For perform' => 'На выполнение',
    'For acceptance' => 'На подтверждение',
    'Not accepted' => 'Не подтверждена',
    'Executing' => 'Выполняется',
    'Executed' => 'Выполнена',
    'Perform failed' => 'Ошибка выполнения',
    'The creator of command' => 'Создатель команды',
    'Accept' => 'Принять',
    'Reject' => 'Отклонить',
    'Are you sure you want to reject a command?' => 'Вы уверены, что хотите отклонить команду?',
    'Command was approved' => 'Команда была подтверждена',
    'Command was rejected' => 'Команда была отклонена',
    'Error of approving' => 'Ошибка подтверждения',
    'Error of rejecting' => 'Ошибка отклонения',
    'Reject reason' => 'Причина отклонения',
	'Enable' => 'Включить',
	'Enabled' => 'Включен',
	'Disabled' => 'Выключен',
	'Disable' => 'Выключить',
    'Columns settings' => 'Настройки полей',

	'Document id #{id} was authorized' => 'Документ #{id} авторизован',
    /**
     * Help
     */
    'Terminal version' => 'Версия терминала',

	/**
	 * CompoundCondition
	 */
	'Condition deleted' => 'Условие удалено',
	'Cannot delete condition - check parameters' => 'Не удалось удалить условие - проверьте параметры',
    'No sum ranges are defined - use "Add Sum Range" button' => 'Не задано ни одного диапазона сумм - используйте кнопку "Добавить диапазон сумм"',
    'Add Sum Range' => 'Добавить диапазон сумм',
	'Condition not found' => 'Условие не найдено',
	'LSO security officer' => 'Офицер безопасности LSO',
	'RSO security officer' => 'Офицер безопасности RSO',

    'Routing' => 'Маршрутизация',
    'Action' => 'Действие',
    'Documents not selected' => 'Документы не выбраны',
    'User guide' => 'Руководство пользователя',
    'Administrator\'s guide' => 'Руководство администратора',
    'Installation manual' => 'Руководство по установке',

    'Permissions'   => 'Права',
    'Extended permissions' => 'Расширенные права',
    'Activate XML export' => 'Использовать экспорт XML',
    'XML export settings saved' => 'Настройки экспорта XML сохранены',
    'Choose path for xml export' => 'Укажите путь для экспорта XML-файлов',
    'Export status reports' => 'Экспортировать статусы CyberFT',

    'Address' => 'Адрес',
    'Info' => 'Информация',

    'VTB' => 'ВТБ',
    'SBBOL' => 'Сбербанк',
    'SBBOL2' => 'Сбербанк (новый)',
    'Create Free Format document' => 'Создать документ свободного формата',
    'View Free Format document' => 'Просмотр документа свободного формата',
    'No attached documents' => 'Нет приложенных документов',

    'Documents for signing' => 'Документы, ожидающие подписания',
    'https://www.cyberplat.com/' => 'https://www.cyberplat.ru/',
    'CyberPlat LLC' => 'ООО «КИБЕРПЛАТ»',
    'https://cyberplat.com' => 'https://cyberplat.ru',

    'The document is creating. Do not close the page until the document is loaded.' => 'Документ создается. Не закрывайте страницу до завершения загрузки документа.',
    'Loading...' => 'Загрузка...',

    'Please, enter your email address to receive<br/> email with password reset link' => 'Пожалуйста, введите ваш адрес электронной почты, <br/>чтобы мы могли отправить вам письмо со ссылкой<br/> для сброса пароля',
    '<p>Signing service is not installed or is not running.</p><p><a href="http://download.cyberft.ru/CyberSignService/CyberSignService.zip" target="_blank">Download CyberSignService</a></p><p><a href="http://download.cyberft.ru/CyberSignService/CyberSignService_manual.pdf" target="_blank">Setup manual</a></p>'
        => '<p>Не установлен или не запущен сервис подписания CyberSignService.<p><p><a href="http://download.cyberft.ru/CyberSignService/CyberSignService.zip" target="_blank">Загрузить ПО CyberSignService</a></p><p><a href="http://download.cyberft.ru/CyberSignService/CyberSignService_manual.pdf" target="_blank">Инструкция по установке и настройке</a></p>',
    'Authorization request has not been signed' => 'Запрос на вход не был подписан',
    'Automatic statement request' => 'Автоматический запрос выписки',
    'Create statement requests for previous day' => 'Создавать запросы выписки за прошлый день',
    'Create statement requests for current day' => 'Создавать запросы выписки за текущий день',
    'Time of the first request' => 'Время первого запроса',
    'Time of the last request' => 'Время последнего запроса',
    'Repeat every (min)' => 'Повторять каждые (минут)',
    'Days of week' => 'Дни недели',
    'The minimal non-zero value is 10 min' => 'Минимальное возможное ненулевое значение - 10 минут',

    'Time of the first request' => 'Время первого запроса',
    'Time of the last request' => 'Время последнего запроса',
    'Repeat every' => 'Повторять каждые',

    'Sberbank (legacy)' => 'Сбербанк (старый)',
    'Sberbank' => 'Сбербанк',
    'ISO20022' => 'ISO20022',
    'ISO20022 (Rosbank)' => 'ISO20022 (Росбанк)',
    'Raiffeisen' => 'Райффайзен',
    'Preferred exchange format' => 'Предпочитаемый формат обмена',

    'Settings have been updated' => 'Настройки сохранены',
    'Failed to update settings' => 'Не удалось сохранить настройки',

    'Export documents to common directory (.../cyberft/app/export)' => 'Экспортировать документы в общий раздел (.../cyberft/app/export)',
    'Export documents to this terminal directory (.../cyberft/app/export/{terminalAddress})'
        => 'Экспортировать документы в раздел данного терминала (.../cyberft/app/export/{terminalAddress})',
    
    'Enable importing and exporting documents via API for all terminals' => 'Разрешить импорт и экспорт документов через API для всех терминалов',
    'Enable importing and exporting documents via API' => 'Разрешить импорт и экспорт документов через API',
    'Access token' => 'Токен доступа',
    'Generate new token' => 'Сгенерировать новый токен',
    'When generating new token, it is necessary to change it in the external system, otherwise the authorization with the API will be impossible. Generate new token?' => 'При изменении токена доступа необходимо обязательно изменить его во внешней системе, иначе авторизация по API будет невозможна. Сгенерировать новый токен?',
    'Copy' => 'Скопировать',
    'Use additional access token which is limited only to this terminal' => 'Использовать дополнительный токен, который предоставляет доступ только к этому терминалу',
];
