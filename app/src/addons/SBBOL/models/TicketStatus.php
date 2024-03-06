<?php

namespace addons\SBBOL\models;

use Yii;

class TicketStatus
{
    const ACCEPTANCE = 'ACCEPTANCE';
    const ACCEPTED = 'ACCEPTED';
    const ACCEPTED_BY_ABS = 'ACCEPTED_BY_ABS';
    const ACCEPTED_BY_CFE = 'ACCEPTED_BY_CFE';
    const ACCEPTEXPIRE = 'ACCEPTEXPIRE';
    const APPROVE = 'APPROVE';
    const CARD2 = 'CARD2';
    const CLOSED = 'CLOSED';
    const CREATED = 'CREATED';
    const CHECKERROR = 'CHECKERROR';
    const DECLINED_BY_ABS = 'DECLINED_BY_ABS';
    const DECLINED_BY_BANK = 'DECLINED_BY_BANK';
    const DECLINED_BY_CFE = 'DECLINED_BY_CFE';
    const DELAYED = 'DELAYED';
    const DELIVERED = 'DELIVERED';
    const DOCUMENT_NOT_FOUND = 'DOCUMENT_NOT_FOUND';
    const EXPORT_ERROR = 'EXPORT_ERROR';
    const EXPORTED = 'EXPORTED';
    const FAIL = 'FAIL';
    const FORMAT_ERROR = 'FORMAT_ERROR';
    const FRAUDREVIEW = 'FRAUDREVIEW';
    const FRAUDSMS = 'FRAUDSMS';
    const IMPLEMENTED = 'IMPLEMENTED';
    const IMPORTED = 'IMPORTED';
    const INVALIDEDS = 'INVALIDEDS';
    const MODIFYREQUIRED = 'MODIFYREQUIRED';
    const NONEACCEPTANCE = 'NONEACCEPTANCE';
    const ONACCEPTANCE = 'ONACCEPTANCE';
    const ORG_NOT_FOUND = 'ORG_NOT_FOUND';
    const PAID = 'PAID';
    const PARTACCEPT = 'PARTACCEPT';
    const PARTIALLY_ACCEPTED_BY_CFE = 'PARTIALLY_ACCEPTED_BY_CFE';
    const PARTIMPLEMENTED = 'PARTIMPLEMENTED';
    const PARTLY_SIGNED = 'PARTLY_SIGNED';
    const PROCESSED = 'PROCESSED';
    const PROCESSERROR = 'PROCESSERROR';
    const PROCESSING = 'PROCESSING';
    const PUBLISHED_BY_BANK = 'PUBLISHED_BY_BANK';
    const RECALL = 'RECALL';
    const REFUSEDBYBANK = 'REFUSEDBYBANK';
    const REQUISITE_ERROR = 'REQUISITE_ERROR';
    const RESPONSE_DIVISION = 'RESPONSE_DIVISION';
    const RQUID_DUPLIC = 'RQUID_DUPLIC';
    const SENDED_TO_PAYER = 'SENDED_TO_PAYER';
    const SERT_NOT_FOUND = 'SERT_NOT_FOUND';
    const SIGNED = 'SIGNED';
    const SOFT_FAIL = 'SOFT_FAIL';
    const SUBMITTED = 'SUBMITTED';
    const TRIED_BY_CFE = 'TRIED_BY_CFE';
    const VALIDEDS = 'VALIDEDS';

    const STATUSES = [
        self::FORMAT_ERROR => [
            'name'               => null,
            'description'        => 'Запрос не соответствует формату',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::RQUID_DUPLIC => [
            'name'               => 'Дублирующий RqUID',
            'description'        => 'Запрос с таким идентификатором уже был получен и обработан',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::ORG_NOT_FOUND => [
            'name'               => null,
            'description'        => 'Клиент не обслуживаются по ДБО или неверный ORGID',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::DELIVERED => [
            'name'               => 'Доставлен',
            'description'        => 'Запрос доставлен в ДБО и взят в обработку.',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::SERT_NOT_FOUND => [
            'name'               => null,
            'description'        => 'Поиск сертификата для проверки ЭП под ЭД на стороне банка дала отрицательный результат. Нет соответствия статусу документа в ДБО.',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::INVALIDEDS => [
            'name'               => 'ЭП не верна',
            'description'        => 'Проверка ЭП под ЭД на стороне Банка дала отрицательный результат',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::REQUISITE_ERROR => [
            'name'               => 'Ошибка реквизитов',
            'description'        => 'Электронный документ не прошел логические контроли Системы ДБО при приеме на стороне Банка или Запрос на выписку превышает 15 дней',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::DOCUMENT_NOT_FOUND => [
            'name'               => null,
            'description'        => 'Электронный документ с указанным идентификатором не найден в БД',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::ACCEPTED => [
            'name'               => 'Принят',
            'description'        => 'Электронный документ принят на стороне Банка',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::EXPORTED => [
            'name'               => 'Выгружен',
            'description'        => 'Электронный документ выгружен Банком в АБС',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::ACCEPTED_BY_ABS => [
            'name'               => 'Принят АБС',
            'description'        => 'Электронный документ был принят к обработке в АБС Банка',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::CARD2 => [
            'name'               => 'Картотека 2',
            'description'        => 'Электронный документ передан в картотеку в ожидание средств на счету клиента',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::DECLINED_BY_ABS => [
            'name'               => 'Отказан АБС',
            'description'        => 'Электронный документ отказан АБС Банка',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::DECLINED_BY_BANK => [
            'name'               => 'Отказ платежа',
            'description'        => 'Электронный документ отказан',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::DELAYED => [
            'name'               => 'Приостановлен',
            'description'        => 'Обработка электронного документа была приостановлена',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::RECALL => [
            'name'               => 'Отозван',
            'description'        => 'Электронный документ был отозван Клиентом по запросу',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::IMPLEMENTED => [
            'name'               => 'Исполнен',
            'description'        => 'Электронный документ исполнен Банком',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::EXPORT_ERROR => [
            'name'               => 'Ошибка экспорта',
            'description'        => 'Электронный документ не выгрузился в АБС Банка',
            'retryFetchResponse' => true,
            'isError'            => true,
        ],
        self::ACCEPTED_BY_CFE => [
            'name'               => 'Принят ВК',
            'description'        => 'Документ принят валютным контролем',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::DECLINED_BY_CFE => [
            'name'               => 'Отказан ВК',
            'description'        => 'Документ не принят валютным контролем',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::REFUSEDBYBANK => [
            'name'               => 'Отвергнут Банком',
            'description'        => 'Электронный документ отвергнут в СББОЛ Банка вручную.',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::PARTIMPLEMENTED => [
            'name'               => 'Частично Исполнен',
            'description'        => 'Документ исполнен частично',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::FAIL => [
            'name'               => null,
            'description'        => 'Нет соответствия статусу документа. Документы в таком статусе не попадают в СББОЛ. Невалидный документ, в документе содержится критическая ошибка. В этом случае от сервиса УПШ отправляется ответ с разъяснениями ошибки, ответ записывается в таблицу SBNS_UPG_INBOUND',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::SOFT_FAIL => [
            'name'               => null,
            'description'        => 'Ошибка в случае недоступности БД, отсутствие необходимых настроек клиентаю Документ может быть обработан позднее',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::FRAUDREVIEW => [
            'name'               => 'На проверке у специалиста Банка',
            'description'        => 'Со стороны ФРОД-анализа получен статус документа «На проверке у специалиста Банка»',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::FRAUDSMS => [
            'name'               => 'Требуется подтверждение sms-паролем',
            'description'        => 'Со стороны ФРОД-анализа получен статус документа «Требуется подтверждение sms-паролем»',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::SENDED_TO_PAYER => [
            'name'               => 'Отправлен плательщику',
            'description'        => 'Документ отправлен плательщику',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::PROCESSED => [
            'name'               => 'Обработан',
            'description'        => 'Документ обработан',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::ONACCEPTANCE => [
            'name'               => 'На акцепт',
            'description'        => 'Входящее платежное поручение получено из АБС',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::ACCEPTEXPIRE => [
            'name'               => 'Истек срок акцепта',
            'description'        => 'Срок акцепта истек',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::ACCEPTANCE => [
            'name'               => 'Акцептован',
            'description'        => 'В АБС поступило «Заявление на акцепт»',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::NONEACCEPTANCE => [
            'name'               => 'Отказ от акцепта',
            'description'        => '1) В АБС поступило «Заявление об отказе от акцепта», 2) В АБС поступил отзыв ПТ получателем',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::PARTACCEPT => [
            'name'               => 'Частично акцептован',
            'description'        => 'В АБС поступило «Заявление о частичном акцепте»',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::PAID => [
            'name'               => 'Оплачено',
            'description'        => 'Выполнено списание в соответствии с заявлением',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::PROCESSING => [
            'name'               => 'В обработке',
            'description'        => 'Клиент сформировал «Заявление об акцепте/частичном акцепте/отказе от акцепта»',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::APPROVE => [
            'name'               => 'Одобрен',
            'description'        => 'Документ одобрен',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::MODIFYREQUIRED => [
            'name'               => 'Требует доработки',
            'description'        => 'Документ требует доработки',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::PROCESSERROR => [
            'name'               => 'Отказан',
            'description'        => 'Документ отказан',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
        self::SIGNED => [
            'name'               => 'Документ подписан',
            'description'        => 'Документ подписан',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::PARTLY_SIGNED => [
            'name'               => 'Частично подписан',
            'description'        => 'ЭД был подписан неполным набором подписей',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::CLOSED => [
            'name'               => 'Закрыт',
            'description'        => 'Документ закрыт',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::SUBMITTED => [
            'name'               => 'Представлен',
            'description'        => 'Электронный документ принят ВК',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::TRIED_BY_CFE => [
            'name'               => 'Проверяется ВК',
            'description'        => 'Документ принят в работу сотрудником ВК в МВК ЕКС',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::PARTIALLY_ACCEPTED_BY_CFE => [
            'name'               => 'Частично принят ВК',
            'description'        => 'Электронный документ частично принят Валютным контролем',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::RESPONSE_DIVISION => [
            'name'               => null,
            'description'        => 'Статус уведомляет о разбиении большого ответа на пакеты',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::CREATED => [
            'name'               => 'Создан',
            'description'        => 'Документ записан в БД СББОЛ, проверки не выполнялись',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::VALIDEDS => [
            'name'               => 'ЭП верна',
            'description'        => 'Проверка ЭП под ЭД на стороне Банка дала успешный результат',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::PUBLISHED_BY_BANK => [
            'name'               => 'Издан Банком',
            'description'        => 'Издан Банком',
            'retryFetchResponse' => false,
            'isError'            => false,
        ],
        self::IMPORTED => [
            'name'               => 'Импортирован',
            'description'        => 'Документ импортирован',
            'retryFetchResponse' => true,
            'isError'            => false,
        ],
        self::CHECKERROR => [
            'name'               => 'Ошибка контроля',
            'description'        => 'ЭД не прошел логические контроли Системы ДБО на стороне клиента при сохранении',
            'retryFetchResponse' => false,
            'isError'            => true,
        ],
    ];

    private $code;
    private $name;
    private $description;
    private $retryFetchResponse;
    private $isError;

    public function __construct($code)
    {
        if (!array_key_exists($code, static::STATUSES)) {
            Yii::warning(sprintf("Unknown status '%' is interpreted as '%s'", $code, self::FAIL));
            $code = self::FAIL;
        }

        $status = static::STATUSES[$code];
        $this->code = $code;
        foreach ($status as $property => $value) {
            $this->$property = $value;
        }
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

    public function shouldRetryFetchResponse()
    {
        return $this->retryFetchResponse;
    }

    public function isError()
    {
        return $this->isError;
    }
}
