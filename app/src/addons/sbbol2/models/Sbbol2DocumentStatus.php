<?php

namespace addons\sbbol2\models;


class Sbbol2DocumentStatus
{
    const STATUSES = [
        'ACCEPTED' => [
            'name'           => 'Принят',
            'description'    => 'Электронный документ принят на стороне Банка',
            'isFinal'        => false,
            'terminalStatus' => 'ACCP'
        ],
        'ACCEPTED_BY_ABS' => [
            'name'           => 'Принят АБС',
            'description'    => 'Электронный документ был принят к обработке в АБС Банка',
            'isFinal'        => false,
            'terminalStatus' => 'ACTC'
        ],
        'CARD2' => [
            'name'           => 'Картотека 2',
            'description'    => 'Электронный документ передан в картотеку в ожидание средств на счету клиента',
            'isFinal'        => false,
            'terminalStatus' => 'CARD2'
        ],
        'CHECKERROR' => [
            'name'           => 'Ошибка контроля',
            'description'    => 'ЭД сформирован, но при сохранении не прошел проверку корректности заполнения полей и '
            . 'сохранен с имеющимися в нем ошибками.',
            'isFinal'        => false,
            'terminalStatus' => 'RJCT'
        ],
        'CHECKERROR_BANK' => [
            'name'           => 'Ошибка контроля, Банк',
            'description'    => 'ЭД сформирован, но при сохранении не прошел проверку корректности заполнения полей и '
            . 'сохранен с имеющимися в нем ошибками.',
            'isFinal'        => false,
            'terminalStatus' => 'RJCT'
        ],
        'CREATED' => [
            'name'           => 'Создан',
            'description'    => 'Документ записан в БД, проверки не выполнялись',
            'isFinal'        => false,
            'terminalStatus' => 'ACTC'
        ],
        'DELAYED' => [
            'name'           => 'Приостановлен',
            'description'    => 'Обработка электронного документа была приостановлена',
            'isFinal'        => false,
            'terminalStatus' => 'PDNG'
        ],
        'DELETED' => [
            'name'           => 'Удалён',
            'description'    => 'Электронный документ удалён',
            'isFinal'        => true,
            'terminalStatus' => ''
        ],
        'DELIVERED' => [
            'name'           => 'Доставлен',
            'description'    => 'Запрос доставлен в ДБО и взят в обработку',
            'isFinal'        => false,
            'terminalStatus' => 'RCVD'
        ],
        'EXPORTED' => [
            'name'           => 'Выгружен',
            'description'    => 'Электронный документ выгружен Банком в АБС',
            'isFinal'        => false,
            'terminalStatus' => 'ACCP'
        ],
        'EXPORT_ERROR' => [
            'name'           => 'Ошибка экспорта',
            'description'    => 'Электронный документ не выгрузился в АБС Банка',
            'isFinal'        => false,
            'terminalStatus' => 'RJCT'
        ],
        'FRAUDALLOW' => [
            'name'           => 'Одобрен ФРОД',
            'description'    => 'Проверка во ФРОДЕ прошла успешно, переход на «Принят»',
            'isFinal'        => false,
            'terminalStatus' => 'FRAUDALLOW'
        ],
        'FRAUDDENY' => [
            'name'           => 'Отвергнут ФРОД',
            'description'    => 'Документ отказан на основе проверки в АС Fraud-мониторинг, переходим в «Отвергнут банком»',
            'isFinal'        => false,
            'terminalStatus' => 'FRAUDDENY'
        ],
        'FRAUDREVIEW' => [
            'name'           => 'На проверке у специалиста Банка',
            'description'    => 'Со стороны ФРОД-анализа получен статус документа «На проверке у специалиста Банка»',
            'isFinal'        => false,
            'terminalStatus' => 'FRAUDREVIEW'
        ],
        'FRAUDSENT' => [
            'name'           => 'Отправлен во ФРОД',
            'description'    => 'Документ отправлен на проверку в АС Fraud-мониторинг',
            'isFinal'        => false,
            'terminalStatus' => 'FRAUDSENT'
        ],
        'FRAUDSMS' => [
            'name'           => 'Требуется подтверждение sms-паролем',
            'description'    => 'Со стороны ФРОД-анализа получен статус документа «Требуется подтверждение sms-паролем»',
            'isFinal'        => false,
            'terminalStatus' => 'FRAUDSMS'
        ],
        'IMPLEMENTED' => [
            'name'           => 'Исполнен',
            'description'    => 'Электронный документ исполнен Банком',
            'isFinal'        => true,
            'terminalStatus' => 'ACSC'
        ],
        'INVALIDEDS' => [
            'name'           => 'ЭП/АСП не верна',
            'description'    => 'Проверка ЭП под ЭД на стороне Банка дала отрицательный результат',
            'isFinal'        => true,
            'terminalStatus' => 'RJCT'
        ],
        'ON_PROCESSING' => [
            'name'           => 'На обработке',
            'description'    => 'Документ с этим статусом автоматически направляется на обработку клиентом, либо '
            . 'автоматическую обработку по клиентской настройке',
            'isFinal'        => false,
            'terminalStatus' => 'ACCP'
        ],
        'PARTSIGNED' => [
            'name'           => 'Частично подписан',
            'description'    => 'ЭД подписан частью подписей, входящих в предусмотренный для данного документа комплект подписей.',
            'isFinal'        => false,
            'terminalStatus' => ''
        ],
        'PROCESSERROR' => [
            'name'           => 'Отказан',
            'description'    => 'Документ отказан',
            'isFinal'        => true,
            'terminalStatus' => 'RJCT'
        ],
        'PROCESSING' => [
            'name'           => 'В обработке',
            'description'    => 'Клиент сформировал «Заявление об акцепте/частичном акцепте/отказе от акцепта»',
            'isFinal'        => false,
            'terminalStatus' => 'ACCP'
        ],
        'RECALL' => [
            'name'           => 'Отозван',
            'description'    => 'Электронный документ был отозван Клиентом по запросу',
            'isFinal'        => true,
            'terminalStatus' => ''
        ],
        'REFUSEDBYABS' => [
            'name'           => 'Отказан АБС',
            'description'    => 'ЭД не прошел проверки в АБС.',
            'isFinal'        => false,
            'terminalStatus' => 'RJCT'
        ],
        'REQUISITEERROR' => [
            'name'           => 'Ошибка реквизитов',
            'description'    => 'Электронный документ не прошел логические контроли Системы ДБО при приеме на '
            . 'стороне Банка или Запрос на выписку превышает 15 дней',
            'isFinal'        => true,
            'terminalStatus' => 'RJCT'
        ],
        'SENT_TO_ADMIN' => [
            'name'           => 'Передан администратору',
            'description'    => 'Промежуточный транспорт, документ передан Администратору',
            'isFinal'        => false,
            'terminalStatus' => 'SENT_TO_ADMIN'
        ],
        'SIGNED' => [
            'name'           => 'Подписан',
            'description'    => 'ЭД подписан предусмотренным для него комплектом подписей.',
            'isFinal'        => false,
            'terminalStatus' => 'SIGNED'
        ],
        'SUBMITTED' => [
            'name'           => 'Представлен',
            'description'    => 'Электронный документ принят ВК',
            'isFinal'        => false,
            'terminalStatus' => 'ACTC'
        ],
    ];

    private $code;
    private $name;
    private $description;
    private $isFinal;
    private $terminalStatus;
    private $isError;

    public function __construct($code)
    {
        if (!static::isValidStatusCode($code)) {
            throw new \Exception("Unknown status '$code'");
        }

        $status = static::STATUSES[$code];
        foreach ($status as $property => $value) {
            $this->$property = $value;
        }
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }
    
    public function getTerminalStatus()
    {
        return $this->terminalStatus;
    }

    public function isFinal()
    {
        return $this->isFinal;
    }

    public function isError()
    {
        return $this->isError;
    }

    public static function isValidStatusCode($code)
    {
        return array_key_exists($code, static::STATUSES);
    }
}
