<?php

namespace addons\SBBOL\models;

class AuthReturnCode
{
    const SUCCESS = 'SUCCESS';
    const BAD_CREDENTIALS = 'BAD_CREDENTIALS';
    const MUST_CHANGE_PASSWORD = 'MUST_CHANGE_PASSWORD';
    const CERTIFICATE_EXPIRED = 'CERTIFICATE_EXPIRED';
    const ORG_LOCKED = 'ORG_LOCKED';
    const FRAUDMON_DENY = 'FRAUDMON_DENY';
    const IP_CHANGED = 'IP_CHANGED';
    const CONTRACT_FINANCIAL_LOCKED = 'CONTRACT_FINANCIAL_LOCKED';
    const SERVER_ACCESS_ERROR = 'SERVER_ACCESS_ERROR';
    const UNSPECIFIED_ERROR = 'UNSPECIFIED_ERROR';
    const TOO_FREQUENT_LOGIN_FAILS = 'TOO_FREQUENT_LOGIN_FAILS';
    const ACCOUNT_DISABLED = 'ACCOUNT_DISABLED';
    const ACCESS_POINT_NOT_AVAILABLE = 'ACCESS_POINT_NOT_AVAILABLE';
    const CONTRACT_SUSPENDED = 'CONTRACT_SUSPENDED';
    const CONTRACT_TERMINATED = 'CONTRACT_TERMINATED';
    const ACCESS_DENIED_BY_CLIENT_RULES = 'ACCESS_DENIED_BY_CLIENT_RULES';
    const ACCESS_POINT_UPG_NOT_AVAILABLE = 'ACCESS_POINT_UPG_NOT_AVAILABLE';
    const ACCESS_POINT_UPG_HOLDING_NOT_AVAILABLE = 'ACCESS_POINT_UPG_HOLDING_NOT_AVAILABLE';
    const CREDENTIALS_NOT_FOUND_BY_CERTIFICATE = 'CREDENTIALS_NOT_FOUND_BY_CERTIFICATE';
    const NEED_SMS_AUTHENTICATION = 'NEED_SMS_AUTHENTICATION';
    const BAD_SMS_AUTHENTICATION = 'BAD_SMS_AUTHENTICATION';
    const OBSOLETE_SMS_AUTHENTICATION = 'OBSOLETE_SMS_AUTHENTICATION';
    const INCORRECT_SMS_SETTINGS = 'INCORRECT_SMS_SETTINGS';
    const SESSION_NOT_FOUND = 'SESSION_NOT_FOUND';
    const NEED_TOKEN_TO_ACCESS = 'NEED_TOKEN_TO_ACCESS';
    const FRAUDMON_VALIDATE_ERROR = 'FRAUDMON_VALIDATE_ERROR';
    const INCORRECT_ACCESS_POINT = 'INCORRECT_ACCESS_POINT';
    const INVALID_SESSION_ID_FORMAT = 'INVALID_SESSION_ID_FORMAT';
    const ACCESS_POINT_UPG_SBB_NOT_AVAILABLE = 'ACCESS_POINT_UPG_SBB_NOT_AVAILABLE';
    const TOO_FREQUENT_PASSWORD_CHANGE = 'TOO_FREQUENT_PASSWORD_CHANGE';
    const UNKNOWN = 'UNKNOWN';

    private static $responseCodes = [
        '00' => ['id' => self::SUCCESS,                                'description' => 'Операция выполнена успешно',],
        '01' => ['id' => self::BAD_CREDENTIALS,                        'description' => 'Неверный логин/пароль или учетная запись заблокирована',],
        '02' => ['id' => self::MUST_CHANGE_PASSWORD,                   'description' => 'Необходимо сменить пароль',],
        '03' => ['id' => self::CERTIFICATE_EXPIRED,                    'description' => 'Срок действия сертификата истек',],
        '04' => ['id' => self::ORG_LOCKED,                             'description' => 'Офис организации пользователя заблокирован',],
        '05' => ['id' => self::FRAUDMON_DENY,                          'description' => 'В аутентификации отказано ФРОД-мониторингом',],
        '06' => ['id' => self::IP_CHANGED,                             'description' => 'IP изменился',],
        '07' => ['id' => self::CONTRACT_FINANCIAL_LOCKED,              'description' => 'Финансовый договор заблокирован',],
        '08' => ['id' => self::SERVER_ACCESS_ERROR,                    'description' => 'Ошибка доступа к серверу',],
        '09' => ['id' => self::UNSPECIFIED_ERROR,                      'description' => 'Неспецифицированная ошибка',],
        '0A' => ['id' => self::TOO_FREQUENT_LOGIN_FAILS,               'description' => 'Слишком частая ошибка входа в систему',],
        '0B' => ['id' => self::ACCOUNT_DISABLED,                       'description' => 'Учетная запись отключена',],
        '0C' => ['id' => self::ACCESS_POINT_NOT_AVAILABLE,             'description' => 'Точка входа недоступна',],
        '0D' => ['id' => self::CONTRACT_SUSPENDED,                     'description' => 'Ожидается заключение договора',],
        '0E' => ['id' => self::CONTRACT_TERMINATED,                    'description' => 'Договор закрыт',],
        '0F' => ['id' => self::ACCESS_DENIED_BY_CLIENT_RULES,          'description' => 'Доступ закрыт настройками клиента',],
        '10' => ['id' => self::ACCESS_POINT_UPG_NOT_AVAILABLE,         'description' => 'У пользователя в настройках отсутствует точка входа УПШ',],
        '11' => ['id' => self::ACCESS_POINT_UPG_HOLDING_NOT_AVAILABLE, 'description' => 'У пользователя в настройках отсутствует точка входа УПШ_Холдинг',],
        '12' => ['id' => self::CREDENTIALS_NOT_FOUND_BY_CERTIFICATE,   'description' => 'Сертификат не найден в базе данных либо не привязан ни к одному пользователю',],
        '13' => ['id' => self::NEED_SMS_AUTHENTICATION,                'description' => 'Установленная сессия требует подтверждения кодом СМС',],
        '14' => ['id' => self::BAD_SMS_AUTHENTICATION,                 'description' => 'Неверный код СМС',],
        '15' => ['id' => self::OBSOLETE_SMS_AUTHENTICATION,            'description' => 'Срок действия кода СМС истек',],
        '16' => ['id' => self::INCORRECT_SMS_SETTINGS,                 'description' => 'Неверные настройки СМС',],
        '17' => ['id' => self::SESSION_NOT_FOUND,                      'description' => 'Сессия не найдена',],
        '18' => ['id' => self::NEED_TOKEN_TO_ACCESS,                   'description' => 'Пользователь с указаннымиучетными данными должен использовать токен',],
        '19' => ['id' => self::FRAUDMON_VALIDATE_ERROR,                'description' => 'Ошибка валидации параметров ФРОД-мониторинга при аутентификации',],
        '1А' => ['id' => self::INCORRECT_ACCESS_POINT,                 'description' => 'Для организации недоступна точка входа Банк-Клиент',],
        '1B' => ['id' => self::INVALID_SESSION_ID_FORMAT,              'description' => 'Неверный формат значения идентификатора сессии sessionId',],
        '1C' => ['id' => self::ACCESS_POINT_UPG_SBB_NOT_AVAILABLE,     'description' => 'У пользователя в настройках отсутствует точка входа УПШ_СББ',],
        '1D' => ['id' => self::TOO_FREQUENT_PASSWORD_CHANGE,           'description' => 'Слишком частые попытки смены пароля',],
    ];

    private $id;
    private $hexCode;
    private $description;

    public function __construct($binCode)
    {
        $hexCode = strtoupper(bin2hex($binCode));
        $responseCode = self::$responseCodes[$hexCode] ?? ['id' => self::UNKNOWN, 'description' => 'Unknown response code'];
        $this->hexCode = $hexCode;
        $this->id = $responseCode['id'];
        $this->description = $responseCode['description'];
    }

    public function getHexCode()
    {
        return $this->hexCode;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function __toString()
    {
        return $this->id;
    }

}
