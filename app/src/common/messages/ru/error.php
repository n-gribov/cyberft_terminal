<?php

return [
	/** HTTP 400 */
	'Bad Request' => 'Некорректный запрос',
	'The request cannot be fulfilled due to bad syntax.' => 'Запрос к серверу содержит недопустимые параметры.',
	/** HTTP 401 */
	'Unauthorized' => 'Необходимо авторизоваться',
	'Authentication is required.' => 'Для доступа необходимо авторизоваться.',
	/** HTTP 403 */
	'Forbidden' => 'Доступ запрещен',
	'The request was a valid request, but the server you have not access for this page.' => 'Запрашиваемая страница существует, но у вас нет к ней доступа.',
    'Service access denied for this user' => 'Сервис недоступен данному пользователю',
	/** HTTP 404 */
	'Not Found' => 'Страница не найдена',
	'The requested page could not be found.' => 'По запрашиваемому адресу страница не найдена.',
	/** HTTP 500 */
	'Internal Server Error' => 'Внутренняя ошибка сервера',
	'Internal server error happen' => 'Произошла внутренняя ошибка сервера',

    'An error has occurred during communication with the server' => 'Произошла ошибка во время обращения к серверу',

	/** DocManager **/
	'Cannot access doc storage' => 'Нет доступа к хранилищу документов',
	'Cannot write into doc storage' => 'Нет прав на запись в хранилище документов',
	'Unknown document' => 'Неизвестный документ',
	'Document not found' => 'Документ не найден',
	"Status must be 'registered'" => "Статус должен быть 'registered'",
	'Invalid document data' => 'Невалидные данные документа',
	'Exporting document failed' => 'Не удалось экспортировать документ',
	"Status should be '{status}'" => "Статус должен иметь значение '{status}'",
	"Can't save document" => 'Невозможно сохранить документ',
	'Invalid autoprocessing vars' => 'Некорректные переменные автопроцессинга',
	'Private key invalid' => 'Ошибка приватного ключа',
	'Private key invalid password' => 'Неверный пароль приватного ключа',
	'SSL error' => 'Ошибка SSL',
	'Error while writing into envelope' => 'Ошибка записи в конверт',
	'Cannot write into directory' => 'Нет прав на запись в директорию',

	'Invalid source file format' => 'Неверный формат исходного файла',

	'Error: {message}' => 'Ошибка: {message}',
    'Template saved' => 'Шаблон сохранен',
    'Error! Template not saved!' => 'Ошибка! Шаблон не сохранен!',
	'Error! Settings not saved!' => 'Настройки не сохранены!',
	'File not found!' => 'Файл не найден',
	'Limit exceeded the maximum number of uploaded files! You can send no more than {fileCount} files.' => 'Превышен лимит максимального количества отправляемых файлов! Можно отправлять не более {fileCount} файлов.',
    'Incorrect sender' => 'Неверный адрес отправителя',
    'Incorrect recipient' => 'Неверный адрес получателя',
    'Unsupported data format' => 'Неподдерживаемый формат данных',
    'Error occurred while processing document' => 'В процессе обработки документа произошли ошибки',
    'Create type model from file error' => 'Не получилось создать модель из файла',
    'An error has occurred {errorDescription} (status code:{statusCode}, error code: {errorCode})' => 'Произошла ошибка {errorDescription} (код статуса: {statusCode}, код ошибки: {errorCode})',
    'The password must contain at least {number} characters' => 'Пароль должен содержать не менее {number} символов',
    'The password must contain at least one special character' => 'Пароль должен содержать не менее одного специального символа',
    'The password must contain at least one uppercase letter' => 'Пароль должен содержать не менее одного символа в верхнем регистре',
    'The password must contain at least one lowercase letter' => 'Пароль должен содержать не менее одного символа в нижнем регистре',
    'The password must contain at least one digit' => 'Пароль должен содержать не менее одной цифры',
    'The password must be different from the current terminal ID' => 'Пароль не должен совпадать с id текущего терминала',
    'New password must not be the same as the one used previously' => 'Новый пароль не должен совпадать с использованным ранее',
];
