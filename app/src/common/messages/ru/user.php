<?php

return [
	/*
	 * User
	 */
	'Name'		=> 'Имя',
    'e-mail' => 'e-mail',
	'Role'		=> 'Роль',
	'Signature number'  => 'Очередность подписания',
	'Signature level'  => 'Уровень подписания',
	'Level'  => 'Уровень',
    'Signature order' => 'Очередь',
	'Not a signer' => 'Не является подписантом',
	'Status'	=> 'Статус',
	'Created'	=> 'Создан',
	'Changed'	=> 'Изменен',
	'Are you sure you want to delete this user?' => 'Вы уверены, что хотите удалить пользователя?',
    'Are you sure you want to restore this user?' => 'Вы уверены, что хотите восстановить пользователя?',
	'user' => 'пользователя',
	'Reset password' => 'Сбросить пароль',
	'Type of authorization' => 'Способ авторизации',
	'By password' => 'По паролю',
	'By key' => 'По ключу',
    'Last name' => 'Фамилия',
    'First name' => 'Имя',
    'Middle name' => 'Отчество',
    'Owner administrator' => 'Ответственный администратор',
    'User' => 'Пользователь',

    /**
     * User settings tabs
     */
    'Common' => 'Основное',
    'Terminals' => 'Доступные терминалы',
    'Accounts' => 'Доступные счета',
    'Services' => 'Права на функционал',
    'User access' => 'Доступность для пользователя',
	/**
	 * UserAuthCert
	 */
	'User ID' => 'ID пользователя',
	'Public Key' => 'Публичный ключ',
	'Certificate' => 'Сертификат',
	'Active' => 'Активный',
    'Can not find certificate' => 'Сертификат не найден',
    'Authorization failed' => 'Ошибка входа',
    'Calculate certificate fingerprint error. Wrong certificate data' => 'Ошибка расчёт отпечатка сертификата. Не правильные данные сертификата',

	/**
	 * User view
	 */
	'Information' => 'Информация',
	'Certificates' => 'Сертификаты',
    'Available terminals' => 'Список доступных терминалов',
    'Disable terminal select' => 'Отключить визуальное разделение интерфейса по организациям',
    'Access' => 'Доступ',
    'Signature permission' => 'Право подписи',
    'Service' => 'Модуль',
    'Additional services' => 'Дополнительные функции',
    'User data' => 'Данные пользователя',

	/**
	 * Auth view
	 */
	'No certificates for selected user' => 'Не найдены сертификаты для выбранного пользователя',

	/**
	 * Create cert view
	 */
	'Add certificate' => 'Добавить сертификат',

	/*
	 * UserController
	 */
	'New user created with temporary password {password}. Next, configure available for user accounts and rights and activate this user' => 'Создан новый пользователь с временным паролем {password}. Далее настройте для пользователя доступные счета и права на функционал, а также активируйте его',
	'Password was reset at your request, your new password: "{newPass}"' => 'Пароль сброшен по Вашему запросу, новый пароль: "{newPass}"',
	'Failed to delete user' => 'Не удалось удалить пользователя',
	'User marked as deleted' => 'Пользователь помечен как удаленный',
    'Failed to restore user' => 'Не удалось восстановить пользователя',
    'User restored but needs to be activated again' => 'Пользователь восстановлен, но нуждается в повторной активации',
	'Requested page not found' => 'Запрашиваемая страница не найдена',
	'The document has already been signed by user with level {level}' => 'Этот документ уже был подписан пользователем с уровнем {level}',
	'Certificate was created' => 'Сертификат был создан',
    'Certificate create error' => 'Ошибка создания сертификата',
	'Certificate was updated' => 'Сертификат был обновлён',
    'Certificate update error' => 'Ошибка обновления сертификата',
	'Failed to delete certificate' => 'Не удалось удалить сертификат',
	'Certificate was deleted' => 'Сертификат был удалён',
	'Additional settings' => 'Дополнительные настройки',
	'More...' => 'Еще...',
    'Activation key' => 'Ключ активации',
    'Activate' => 'Активировать',
    'Block' => 'Заблокировать',
    'Approve' => 'Одобрить',
    'User will be activated after security officers\' confirmation!' => 'Пользователь будет активирован после подтверждения офицерами безопасности!',
    'Failed to start the activation process!' => 'Ошибка активации!',
    'Permission denied for this operation!' => 'К этой операции доступ запрещен',
    'Operation complete successful' => 'Операция успешно выполнена',
    'Operation failure!' => 'Ошибка! Операция не выполнена!',
    'Error! Command not found!' => 'Ошибка! Команда не найдена!',
    'Operation is not available for user in current status' => 'Данная операция невозможна при текущем статусе пользователя',
    'User not found!' => 'Пользователь не найден',
    'Account activate' => 'Активация аккаунта',
    'Activating' => 'В процессе активации',
    'Activated' => 'Активирован',
    'Activation completed!' => 'Активация выполнена!',
    'Activation failed!' => 'Ошибка активации!',
    'Inactive' => 'Не активирован',
    'Deleted' => 'Удален',
    'User not activated!' => 'Пользователь не активирован!',
    'User is awaiting activation' => 'Пользователь ожидает активации',
    'The system has no security officers! Are you sure you want to automatically activate user?' => 'В системе отсутствуют офицеры безопасности! Вы уверены что хотите активировать пользователя в автоматическом режиме?',
    'Cannot add security officer, there are already active security officers' => 'Невозможно добавить офицера безопасности, так как уже есть активные офицеры безопасности',
    'Cannot add security officer from web interface' => 'Невозможно добавить офицера безопасности через веб-интерфейс',
    'Profile update is not allowed for security officer' => 'Изменение профиля офицера безопасности не разрешено',
    'At least one authorizer is needed for preliminary authorizers to be effective' => 'Необходим как минимум один авторизатор, чтобы предварительные авторизаторы могли работать',
    'No conditions are defined - this user will be able to authorize any document' => 'Не задано ни одного условия - этот пользователь сможет авторизовать любой документ',
    'User updated' => 'Данные пользователя обновлены',
    'Failed to update user' => 'Не удалось обновить данные пользователя',
    'These conditions are already defined' => 'Такие условия уже заданы',
    'Failed to save user settings' => 'Не удалось сохранить настройки пользователя',
    'Approval required' => 'Требуется одобрение LSO/RSO',
    'RSO approval required' => 'Требуется одобрение RSO',
    'LSO approval required' => 'Требуется одобрение LSO',
    'Approve new settings' => 'Одобрить новые настройки',
    'Settings updated' => 'Настройки сохранены',
    'User will be unblocked after security officers\' confirmation' => 'Пользователь будет разблокирован после подтверждения офицерами безопасности!',
    'Editing user is not allowed' => 'Не разрешается редактировать пользователя',
    'The password has expired' => 'Срок действия пароля истек',
    'Unknown user - data was not updated' => 'Неизвестный пользователь - данные не были сохранены',
    'Not specified available terminals' => 'Не указаны доступные терминалы',
    'Login failed' => 'Ошибка входа',
    'User is blocked' => 'Пользователь заблокирован',

    // Переводы CommonUserExt (дополнительные сервисы пользователя)
    'Certificates management' => 'Управление сертификатами',
    'Allow access to certificate settings' => 'Доступ к работе с сертификатами',
    'Could not save user permissions' => 'Сохранение прав пользователя не удалось',
    'Documentation widgets management' => 'Управление виджетами документации',
    'View import documents errors journal' => 'Просмотр журнала ошибок импорта документов',

    //Permissions
    'View documents'  => 'Просмотр документов',
    'Delete documents'  => 'Удаление документов',
    'Create documents' => 'Создание документов',
    'Sign documents' => 'Подписание документов',
    'Document types permissions' => 'Права по типам документов',

    'Invalid certificate file format' => 'Некорректный формат файла сертификата',
    'Certificate already exists' => 'Этот сертификат был загружен ранее',
    'The certificate has expired! Could not load expired certificate!' => 'Срок действия сертификата истек! Невозможно добавить сертификат с истекшим сроком действия!'
];
