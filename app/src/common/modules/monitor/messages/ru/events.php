<?php
return [
    // Event labels
    'FailedLogin'   => 'Пользователь заблокирован',
    'DocumentForSigning'   => 'Требуется подписание',
    'DocumentStatusChange' => 'Изменение статуса',
    'DocumentBusinessStatusChange' => 'Изменение бизнес-статуса',
    'CryptoProSigningError' => 'КриптоПро',
    'ViewDocument' => 'Просмотр документа',
    'CreateDocument' => 'Создание документа',
    'VerifyDocumentError' => 'Ошибка верификации документа',
    'VerifyDocumentSuccess' => 'Верификация документа',
    'SignDocument' => 'Подписание документа',
    'SendDocument' => 'Отправка документа',
    'PrintDocument' => 'Печать документа',
    'PreAuthDocument' => 'Предварительная авторизация документа',
    'AuthDocument' => 'Авторизация документа',
    'CorrectDocument' => 'Маршрутизация документа на исправление',
    'DeleteDocument' => 'Удаление документа',
    'DocumentProcessError' => 'Ошибка обработки документа',
    'DocumentRegistered' => 'Документ зарегистрирован',
    'ISOReceiveStatus' => 'Получен статус ISO-документа',
    'DocumentContSignError' => 'Ошибка подписания документа',
    'DocumentDublicateError' => 'Ошибка дублирования документа',
    'ISOChangeSettings' => 'Изменение настроек ISO20022',

    // Notifications UI
    'There are no configurable parameters' => 'Нет конфигурируемых параметров',
    'Event code' => 'Тип события',
    'Settings' => 'Настройки',
    'Documents waiting for signing' => 'Документы, ожидающие подписания',
    'Undelivered messages' => 'Недоставленные документы',
    'User {user} failed to login several times' => 'Пользователь {user} несколько раз неуспешно авторизовался',
    'No access to processing ({message})' => 'Нет доступа к процессингу ({message})',

    // Stomp
    'Wrong STOMP config params' => 'Неправильные настройки STOMP',
    'Connection error' => 'Ошибка соединения',
    'Unknown error' => 'Неизвестная ошибка',

    '{type}: Document #{link} signing required'   => '{type}: Документ №{link} требует подписания',
    'Terminal {terminalId}: {type} Document #{link} got status "{status}" after "{previousStatus}"'
        => 'Терминал {terminalId}: Для документа {type} №{link} был установлен статус "{status}" после "{previousStatus}"',

    'SftpOpenFailed'   => 'Ошибка SFTP',
    '{service}: Failed to open resource "{path}"'   => '{service}: Не удается открыть папку "{path}"',
    'Failed to open SFTP resource'   => 'Ошибка открытия папки SFTP',
    'Document processing error' => 'Ошибка обработки документа',
    'Failed login attempts' => 'Ошибки авторизации пользователя',
    'Processing connection fail' => 'Сбой подключения к процессингу',
    'Delivery period in days' => 'Период проверки, дней',
    'Time period' => 'Период времени',
    'Document export' => 'Экспорт документа',

    'Change status from [{previousStatus}] to [{status}] for document ({documentId}){info}' => 'Смена статуса с [{previousStatus}] на [{status}] для документа ({documentId}){info}',
    'Registered new {type} document #{link}' => 'Зарегистрирован новый {type} документ #{link}',
    'New received documents' => 'Новые полученные документы',

    'detailed info: {info}' => 'дополнительные сведения: {info}',

    'emergency' => 'Авария',
    'alert' => 'Тревога',
    'critical' => 'Критический',
    'error' => 'Ошибка',
    'warning' => 'Предупреждение',
    'notice' => 'Замечание',
    'info' => 'Информационный',
    'debug' => 'Отладка',

    'STOMP connection failed' => 'Ошибка соединения STOMP',
    'CFTCP connection failed' => 'Ошибка соединения CFTCP',
    'SFTP connection failed' => 'Ошибка соединения SFTP',

    // initiators
    'Administrator' => 'Главный администратор',
    'User' => 'Пользователь',
    'Security officer (left)' => 'Офицер безопасности (левый)',
    'Security officer (right)' => 'Офицер безопасности (правый)',
    'System' => 'Система',
    'Additional administrator' => 'Администратор',

    // Ext event
    '{initiator} {user} create {documentType} document {document}' => '{initiator} {user} создал {documentType} документ {document}',
    '{initiator} {user} view document {document}' => '{initiator} {user} просмотрел документ {document}',
    '{initiator} {user} print document {document}' => '{initiator} {user} инициировал печать документа {document}',
    '{initiator} {user} create document SWIFTFIN template {template}' => '{initiator} {user} создал шаблон ({template}) SWIFTFIN документа',
    '{initiator} {user} create statement request on account {account}' => '{initiator} {user} создал запрос выписки по счету {account}',
    '{initiator} {user} success login using password, ip {ip}' => '{initiator} {user} успешно авторизовался в системе по логину/паролю, ip {ip}',
    'User {user} failed login using password' => 'Пользователь {user} совершил неуспешную попытку авторизации в системе по логину/паролю',
    'User {user} failed login using personal key' => 'Пользователь {user} совершил неуспешную попытку авторизации в системе по персональному ключу',
    '{initiator} {user} success login using personal key, ip {ip}' => '{initiator} {user} успешно авторизовался в системе по личному ключу, ip {ip}',
    '{initiator} {user} document verify error {document}' => '{initiator} {user} ошибка при верификации документа {document}',
    '{initiator} {user} send document ({document}) to correct' => '{initiator} {user} отправил документ ({document}) на исправление',
    '{initiator} {user} edit document ({document})' => '{initiator} {user} изменил документ ({document})',
    '{initiator} {user} verify document ({document})' => '{initiator} {user} верифицировал документ ({document})',
    '{initiator} {user} create user {newUser}' => '{initiator} {user} создал пользователя {newUser}',
    '{initiator} {user} delete user {newUser}' => '{initiator} {user} удалил пользователя {newUser}',
    '{initiator} {user} restore user {newUser}' => '{initiator} {user} восстановил пользователя {newUser}',
    '{initiator} {user} activate user {newUser}' => '{initiator} {user} активировал пользователя {newUser}',
    '{initiator} {user} edit user {newUser}' => '{initiator} {user} отредактировал пользователя {newUser}',
    '{initiator} {user} stop CyberFT autoprocesses' => '{initiator} {user} остановил автоматические процессы обмена с сетью CyberFT',
    '{initiator} {user} start CyberFT autoprocesses' => '{initiator} {user} запустил автоматические процессы обмена с сетью CyberFT',
    '{initiator} {user} edit CyberFT autoprocesses settings' => '{initiator} {user} внес изменения в настройки обмена с сетью CyberFT',
    '{initiator} {user} edit security settings' => '{initiator} {user} внес изменения в настройки безопасности',
    '{initiator} {user} edit general settings on terminal {terminal}' => '{initiator} {user} внес изменения в общие настройки терминала {terminal}',
    '{initiator} {user} edit terminal {terminal} settings' => '{initiator} {user} внес изменения в настройки Терминала {terminal}',
    '{initiator} {user} create terminal {terminal}' => '{initiator} {user} создал новый Терминал {terminal}',
    '{initiator} {user} create CyberFT members update request' => '{initiator} {user} создал запрос на обновление реестра Участников CyberFT',
    '{initiator} {user} upload Swift codes list' => '{initiator} {user} загрузил справочник Swift-кодов',
    '{initiator} {user} upload EDM banks list' => '{initiator} {user} загрузил справочник Банков',
    '{initiator} {user} create certificate {id}' => '{initiator} {user} добавил сертификат с Id ({id})',
    '{initiator} {user} edit certificate {id}' => '{initiator} {user} отредактировал сертификат с Id ({id})',
    '{initiator} {user} delete certificate {id}' => '{initiator} {user} удалил сертификат с Id ({id})',
    '{initiator} {user} edit swiftfin settings - activate routing' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - активирована маршрутизация swift документов',
    '{initiator} {user} edit swiftfin settings - deactivate routing' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - деактивирована маршрутизация swift документов',
    '{initiator} {user} edit swiftfin settings - activate document export' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - активирован экспорт документов SwiftFIN в xml',
    '{initiator} {user} edit swiftfin settings - deactivate document export' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - деактивирован экспорт документов SwiftFIN в xml',
    '{initiator} {user} edit swiftfin settings - activate CyberFT format document export' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - активирован экспорт документов в формате CyberFT',
    '{initiator} {user} edit swiftfin settings - deactivate CyberFT format document export' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - деактивирован экспорт документов в формате CyberFT',
    '{initiator} {user} edit swiftfin settings - activate document MT011 export' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - активирован экспорт документов MT011',
    '{initiator} {user} edit swiftfin settings - deactivate document MT011 export' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - деактивирован экспорт документов MT011',
    '{initiator} {user} edit swiftfin settings - activate SWIFTFIN document printing {type}' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - активировал печать документов SwiftFIN {type}',
    '{initiator} {user} edit swiftfin settings - activate SWIFTFIN document verification {type}' => '{initiator} {user} внес изменения в общие настройки модуля SwiftFIN - активировал верификацию документов SwiftFIN {type}',
    '{initiator} {user} edit swiftfin settings - edit SWIFTFIN user roles {id}' => '{initiator} {user} внес изменения в настройки ролей пользователя {id} модуля SwiftFIN',
    '{initiator} {user} edit edm settings - activate incoming statement 1c export' => '{initiator} {user} внес изменения в настройки модуля EDM - включен экспорт входящих выписок в формате 1С',
    '{initiator} {user} edit edm settings - add new account ({id})' => '{initiator} {user} внес изменения в настройки модуля EDM - добавлен счет ({id})',
    '{initiator} {user} edit edm settings - add new payment purpose ({id})' => '{initiator} {user} внес изменения в настройки модуля EDM - добавлена запись ({id}) в справочник назначения Платежа',
    '{initiator} {user} edit edm settings - add new contractor ({id})' => '{initiator} {user} внес изменения в настройки модуля EDM - добавлена запись ({id}) в справочник Контрагентов',
    '{initiator} {user} edit ISO20022 settings - change terminal convert code' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - изменен код терминала при преобразовании BIC в адрес терминала',
    '{initiator} {user} edit ISO20022 settings - activate sender/receiver search in document body' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - активирован поиск отправителя и получателя внутри документа при импорте',
    '{initiator} {user} edit ISO20022 settings - deactivate sender/receiver search in document body' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - деактивирован поиск отправителя и получателя внутри документа при импорте',
    '{initiator} {user} edit ISO20022 settings - edit SFTP settings' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - внес изменения в настройки SFTP',
    '{initiator} {user} edit ISO20022 settings - activate CryptoPro signing' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - активировано подписание КриптоПРО',
    '{initiator} {user} edit ISO20022 settings - deactivate CryptoPro signing' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - деактивировано подписание КриптоПРО',
    '{initiator} {user} edit ISO20022 settings - activate use certificate serial number instead fingerprint' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - активировано использование серийного номера сертификата вместо отпечатка',
    '{initiator} {user} edit ISO20022 settings - deactivate use certificate serial number instead fingerprint' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - деактивировано использование серийного номера сертификата вместо отпечатка',
    '{initiator} {user} edit CryptoPro key settings ({fingerprint})' => '{initiator} {user} внес изменения в настройки ключа КриптоПро ({fingerprint})',
    '{initiator} {user} edit ISO20022 settings - add certificate ({fingerprint}) for verification incoming message from ({terminal})' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - добавлен сертификат ({fingerprint}) для верификации входящих сообщений от Участника ({terminal})',
    '{initiator} {user} edit ISO20022 settings - add/edit type document code ({id})' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - добавил (изменил) запись ({id}) в справочнике типов кодов документов ISO20022',
    '{initiator} {user} edit Fileact settings - activate CryptoPro signing' => '{initiator} {user} внес изменения в общие настройки модуля Fileact - активировано подписание КриптоПРО',
    '{initiator} {user} edit Fileact settings - deactivate CryptoPro signing' => '{initiator} {user} внес изменения в общие настройки модуля Fileact - деактивировано подписание КриптоПРО',
    '{initiator} {user} edit Fileact settings - activate use certificate serial number instead fingerprint' => '{initiator} {user} внес изменения в общие настройки модуля Fileact - активировано использование серийного номера сертификата вместо отпечатка',
    '{initiator} {user} edit Fileact settings - deactivate use certificate serial number instead fingerprint' => '{initiator} {user} внес изменения в общие настройки модуля Fileact - деактивировано использование серийного номера сертификата вместо отпечатка',
    '{initiator} {user} edit Fileact settings - add certificate ({fingerprint}) for verification incoming message from ({terminal})' => '{initiator} {user} внес изменения в общие настройки модуля Fileact - добавлен сертификат ({fingerprint}) для верификации входящих сообщений от Участника ({terminal})',
    '{initiator} {user} edit signing settings for module {id} on terminal {terminal}' => '{initiator} {user} внес изменения в настройки подписания для канала {id} на терминале {terminal}',
    '{initiator} {user} edit notify settings' => '{initiator} {user} внес изменения в параметры событий оповещения',
    '{initiator} {user} edit mail notify settings' => '{initiator} {user} внес изменения в параметры рассылки оповещений',
    '{initiator} {user} edit mail list settings' => '{initiator} {user} внес изменения в настройки почтовых оповещений',
    '{initiator} {user} edit incoming verification settings' => '{initiator} {user} внес изменения в настройки верификации входящих сообщений',
    '{initiator} {user} edit autosigning terminal ({id}) settings' => '{initiator} {user} внес изменения в настройки автоматического подписания для терминала получателя ({id})',
    '{initiator} {user} create controller key ({id})' => '{initiator} {user} создал ключ контролера с id ({id})',
    '{initiator} {user} edit controller key ({id})' => '{initiator} {user} внес изменения в параметры ключа контролера с id ({id})',
    '{initiator} {user} delete controller key ({id})' => '{initiator} {user} удалил ключ контролера с id ({id})',
    '{initiator} {user} preauthorized document ({id})' => '{initiator} {user} авторизовал документ ({id})',
    '{initiator} {user} authorized document ({id})' => '{initiator} {user} авторизовал документ ({id})',
    '{initiator} {user} approve user settings {id}' => '{initiator} {user} одобрил настройки пользователя {id}',
    '{initiator} {user} delete document {id}' => '{initiator} {user} удалил документ {id}',
    '{initiator} {user} sign document {id}' => '{initiator} {user} подписал документ {id}',
    '{initiator} {user} send document {id}' => '{initiator} {user} инициировал отправку документа {id}',
    '{initiator} {user} rejected signing document {id}' => '{initiator} {user} отказал в подписании документа {id}',
    '{initiator} {user} input incorrect activation code' => '{initiator} {user} совершил попытку ввода не корректного кода активации',
    '{initiator} {user} input correct activation code' => '{initiator} {user} успешно активировал свою учетную запись кодом активации',
    '{initiator} {user} edit ISO20022 settings - activate keep original filename on export' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - активировано сохранение оригинального имени файла при экспорте',
    '{initiator} {user} edit ISO20022 settings - deactivate keep original filename on export' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - деактивировано сохранение оригинального имени файла при экспорте',
    'Receive new pain.002 document: Change status {document} to {status}' => 'Получен новый документ pain.002: Смена статуса {document} на {status}',
    'CryptoPro license expired' => 'Срок действия лицензии КриптоПро истек',
    'CryptoPro license status' => 'Статус лицензии КриптоПро',
    'Expire CryptoPro certificate' => 'Истечение лицензии КриптоПро CSP',
    '{type}: Document #{link}: can\'t find active controller certificate' => '{type}: Документ #{link}: Не найден активный ключ контролера для подписания',
    '{initiator} {user} changed certificate ({title}) status to {status}. Reason - {reason}' => '{initiator} {user} поменял статус сертификата ({title}) на {status}. Причина - {reason}',
    'Change certificate status' => 'Изменение статуса сертификата',
    'Expire certificates' => 'Окончание срока действия сертификатов',
    'Notify days' => 'Напомнить за, дней',
    'Document processing failed ({type}, {uuid}). Dublicate document ({document})' => 'Ошибка обработки документа ({type}, {uuid}). Дубль документа ({document})',
    '{initiator} {user} edit ISO20022 settings - activate validate documents against XSD on document import' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - активирована валидация документов по XSD при импорте',
    '{initiator} {user} edit ISO20022 settings - deactivate validate documents against XSD on document import' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - деактивирована валидация документов по XSD при импорте',
    '{initiator} {user} edit ISO20022 settings - activate generate unique attachment filename' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - активировано обеспечение уникальности имени файла вложения',
    '{initiator} {user} edit ISO20022 settings - activate export document to IBank format' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - активирован экспорт документов в формат IBank',
    '{initiator} {user} edit ISO20022 settings - deactivate export document to IBank format' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - деактивирован экспорт документов в формат IBank',
    '{initiator} {user} edit ISO20022 settings - deactivate generate unique attachment filename' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - деактивировано обеспечение уникальности имени файла вложения',
    '{initiator} {user} edit ISO20022 settings - activate export incoming statements to 1C format' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - активирован экспорт входящих выписок в формат 1С',
    '{initiator} {user} edit ISO20022 settings - deactivate export incoming statements to 1C format' => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - деактивирован экспорт входящих выписок в формат 1С',
    '{initiator} document ({id}) exported to {path}' => 'Экспорт документа ({id}) - {path}',
    'Document ({document}) - change business status to {businessStatus} from incoming {report}' => 'Смена бизнес-статуса документа ({document}) на {businessStatus}, согласно входящему {report}',
    'Terminal {terminalId}: {type} Document #{link} has inactive certificate "{certLink}"' => 'Терминал {terminalId}: Документ {type} №{link}: сертификат неактивен {certLink}',
    'Terminal {terminalId}: {type} Document #{link} has expired certificate "{certLink}"' => 'Терминал {terminalId}: Документ {type} №{link}: истек срок действия сертификата {certLink}',
    'Terminal {terminalId}: {type} Document #{link} has unknown certificate "{fingerprint}"' => 'Терминал {terminalId}: Документ {type} №{link}: сертификат не найден {fingerprint}',
    '{initiator} {user} edit ISO20022 settings - activate export receipts'
        => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - активирован экспорт квитанций',
    '{initiator} {user} edit ISO20022 settings - deactivate export receipts'
        => '{initiator} {user} внес изменения в общие настройки модуля ISO20222 - деактивирован экспорт квитанций',
    'Password reset for {email} has been requested' => 'Запрошено восстановление пароля на адрес {email}',
    'Password reset for user {user} has been requested, email: {email}' => 'Запрошено восстановление пароля пользователя {user} на адрес {email}',
    'User {user} has reset password' => 'Пользователь {user} установил новый пароль',
    '{initiator} {user} edit VTB settings - add certificate ({fingerprint}) for verification incoming message to ({terminal})'
        => '{initiator} {user} внес изменения в общие настройки модуля ВТБ - добавлен сертификат ({fingerprint}) для верификации входящих сообщений для Участника ({terminal})',
    '{initiator} {user} edit terminal {terminal} VTB integration settings' => '{initiator} {user} внес изменения в общие настройки интеграции с ВТБ для терминала {terminal}',
];